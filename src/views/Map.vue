<template>
    <div>
        <div v-if="!mapLoaded && mission != null" class="overlay" :style="'background: #ccc url(' + mission.backgroundUrl + ') no-repeat; background-size: cover'">
            <div class="overlay-container">
                <img
                    class="img-fluid"
                    :src="loadingTile"
                    alt="Mission Thumbnail"
                />
                <div class="footer">
                    <div class="footer-image">
                        <img
                            src="/img/game-icons/mission-inverted.png"
                            class="img-fluid"
                            :alt="$t('mission-icon')"
                        />
                    </div>
                    <div class="footer-text">
                        <h2>{{ lang('mission-types.' + mission.missionType.toLowerCase(), mission.missionType) }}</h2>
                        <h1>{{ lang('missions.' + mission.slug, mission.name) }}</h1>
                    </div>
                    <div
                        class="loader"
                        style="display: inline-block; height: 48px; float: right"
                    >
                        <video
                            loop
                            muted
                            autoplay
                            style="height: 50px; width: 100px; object-fit: fill"
                        >
                            <source
                                src="/video/loader.webm"
                                type="video/webm"
                            />
                            <source src="/video/loader.mp4" type="video/mp4" />
                        </video>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="mission != null" class="content">
            <div
                class="floor-toggle"
                v-if="mission.missionType != 'Sniper Assassin'"
            >
                <div
                    v-for="i in range(
                        mission.lowestFloorNumber,
                        mission.highestFloorNumber
                    ).reverse()"
                    :key="i"
                    class="floor-info"
                    :class="{ selected: currentLayer === i }"
                >
                    <div @click="changeLevel(i)" class="floor">
                        {{ $t('map.level-number', { levelNumber: i }) }}
                    </div>
                    <div
                        :class="{ 'has-search-results': hasSearchResults(i) }"
                        class="item-count"
                    >
                        {{ calculateNumber(i) }}
                    </div>
                </div>
                <div
                    class="floor-info text-center "
                    :class="{ selected: currentLayer === -99 }"
                    v-if="mission.satelliteView"
                >
                    <div @click="changeLevel(-99)" class="floor">
                        {{ $t('map.satellite') }}
                    </div>
                </div>
            </div>
            <div id="map"></div>
            <nav class="navbar navbar-fixed-right navbar-dark">
                <button
                    class="navbar-toggler"
                    type="button"
                    data-toggle="collapse"
                    data-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent"
                    aria-expanded="false"
                    :aria-label="$t('map.toggle-navigation')"
                >
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div
                    class="collapse navbar-collapse"
                    id="navbarSupportedContent"
                >
                    <div class="header">
                        <router-link :to="{ name: 'home' }">
                            <img
                                src="/img/png/logos/hitmaps.png"
                                class="img-fluid"
                            />
                        </router-link>
                    </div>
                    <div class="editor-enabled" v-if="editor.enabled">
                        <h3 v-if="editor.mode === ''">
                            <i class="fas fa-fw fa-pencil-alt"></i>
                            {{ $t('map.editor-enabled' )}}
                        </h3>
                        <h3 v-else-if="editor.mode === 'items'">
                            <i class="fas fa-fw fa-map-marker-alt"></i>
                            {{ $t('map.add-remove-items') }}
                        </h3>
                        <h3 v-else-if="editor.mode === 'ledges'">
                            <i class="fas fa-fw fa-bezier-curve"></i>
                            {{ $t('map.add-remove-ledges') }}
                        </h3>
                        <h3 v-else-if="editor.mode === 'foliage'">
                            <i class="fas fa-fw fa-leaf"></i>
                            {{ $t('map.add-remove-foliage') }}
                        </h3>
                        <h3 v-else-if="editor.mode === 'disguises'">
                            <i class="fas fa-fw fa-user-tie"></i>
                            {{ $t('map.manage-disguise-areas') }}
                        </h3>
                    </div>
                    <div id="map-control">
                        <div class="control-buttons">
                            <button data-toggle="modal" class="btn control-button" data-target="#locale-modal" v-tooltip:bottom="$t('language-modal.change-language')">
                                <country-flag :country="getCountryFlag()" />
                            </button>
                            <button
                                v-if="isLoggedIn"
                                id="edit-button"
                                @click="toggleEditor"
                                class="btn control-button"
                                v-tooltip:top="$t('map.edit-map')"
                            >
                                <i class="fas fa-pencil-alt"></i>
                            </button>
                            <router-link :to="{ name: 'profile' }">
                                <button
                                    v-if="isLoggedIn"
                                    class="btn control-button"
                                    v-tooltip:top="$t('profile.profile')"
                                >
                                    <i class="fas fa-user-circle"></i>
                                </button>
                            </router-link>
                            <button
                                v-if="isLoggedIn"
                                class="btn control-button"
                                v-tooltip:top="$t('authentication.log-out')"
                                @click="logout"
                            >
                                <i class="fas fa-sign-out-alt"></i>
                            </button>
                            <router-link :to="{ name: 'login' }">
                                <button
                                    v-if="!isLoggedIn"
                                    class="btn control-button"
                                    v-tooltip:bottom="$t('map.login-to-edit')"
                                >
                                    <i class="fas fa-sign-in-alt"></i>
                                </button>
                            </router-link>
                        </div>
                    </div>
                    <br />
                    <div
                        class="accordion"
                        id="accordion"
                        v-show="!editor.enabled"
                    >
                        <div
                            class="floor-toggle"
                            v-if="mission.missionType != 'Sniper Assassin'"
                        >
                            <div
                                v-for="i in range(
                                    mission.lowestFloorNumber,
                                    mission.highestFloorNumber
                                ).reverse()"
                                :key="i"
                                class="floor-info"
                                :class="{ selected: currentLayer === i }"
                            >
                                <div @click="changeLevel(i)" class="floor">
                                    {{ $t('map.level-number', { levelNumber: i }) }}
                                </div>
                                <div
                                    :class="{
                                        'has-search-results': hasSearchResults(
                                            i
                                        )
                                    }"
                                    class="item-count"
                                >
                                    {{ calculateNumber(i) }}
                                </div>
                            </div>
                            <div
                                class="floor-info text-center "
                                :class="{ selected: currentLayer === -99 }"
                            >
                                <div @click="changeLevel(-99)" class="floor">
                                    {{ $t('map.satellite' )}}
                                </div>
                            </div>
                        </div>
                        <div
                            class="search-box"
                            id="search-box-items"
                            data-search="items"
                        >
                            <select
                                @change="searchItem"
                                ref="itemSearch"
                                name="search"
                                class="selectpicker"
                                data-live-search="true"
                                data-title="<i class='fas fa-fw fa-search'></i> Search"
                                data-style="control-button"
                            >
                                <template
                                    v-for="(type, key) in searchableNodes"
                                >
                                    <optgroup
                                        v-for="group in type.items"
                                        :key="key + group.name"
                                        :label="group.name"
                                    >
                                        <option
                                            v-for="node in group.items"
                                            :key="node.name"
                                            :data-layer="key + '|' + group.name"
                                            :value="
                                                key +
                                                    '|' +
                                                    group.name +
                                                    ';' +
                                                    node.name
                                            "
                                        >
                                            {{ node.name }}
                                        </option>
                                    </optgroup>
                                </template>
                            </select>
                            <button
                                @click="clearSearch"
                                id="clear-search"
                                class="btn control-button"
                                v-tooltip:top="$t('map.clear-search')"
                                v-show="searchedItem != null"
                            >
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <div
                            v-if="mission.missionType != 'Sniper Assassin'"
                            class="search-box"
                            id="search-box-disguises"
                            data-search="disguises"
                        >
                            <div class="card">
                                <div class="card-header" id="header-disguises">
                                    <div
                                        class="name collapsed control-button"
                                        data-toggle="collapse"
                                        data-target="#body-disguises"
                                        aria-expanded="false"
                                        aria-controls="body-disguises"
                                    >
                                        <i class="far fa-fw fa-user-tie"></i>
                                        <span class="disguise-text">
                                            {{
                                                currentDisguise === null
                                                    ? $t('map.disguises')
                                                    : currentDisguise.name
                                            }}
                                        </span>
                                        <span class="float-right">
                                            <i class="fas fa-caret-down"></i>
                                            <i class="fas fa-caret-up"></i>
                                        </span>
                                    </div>
                                </div>
                                <div
                                    id="body-disguises"
                                    class="collapse"
                                    aria-labelledby="header-disguises"
                                >
                                    <div class="card-body disguises">
                                        <div
                                            @click="changeDisguise('NONE')"
                                            data-disguise-id="NONE"
                                            class="full-width selected"
                                            style="background: url('/img/jpg/disguises/none.jpg'); "
                                        >
                                            <p class="disguise-info">{{ $t('map.none') }}</p>
                                        </div>
                                        <div
                                            @click="changeDisguise(disguise)"
                                            :data-disguise-id="disguise.id"
                                            v-for="disguise in disguises"
                                            :key="disguise.id"
                                            class="full-width"
                                            :style="{
                                                background:
                                                    'url(/img/jpg/' +
                                                    disguise.image +
                                                    '.jpg)'
                                            }"
                                        >
                                            <p class="disguise-info">
                                                {{ disguise.name }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button
                                id="clear-disguise-search"
                                class="btn control-button"
                                v-tooltip:top="$t('map.clear-search')"
                                style="display: none"
                            >
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <div class="hide-or-select-all">
                            <button
                                id="hide-all"
                                @click="toggleLayer('|', true)"
                                class="btn control-button"
                            >
                                <i class="far fa-fw fa-eye-slash"></i>
                                {{ $t('map.hide-all') }}
                            </button>
                            <button
                                id="show-all"
                                @click="toggleLayer('|', false)"
                                class="btn control-button"
                            >
                                <i class="far fa-fw fa-eye"></i>
                                {{ $t('map.show-all') }}
                            </button>
                        </div>
                        <div v-if="mission.missionType == 'Sniper Assassin'">
                            <div
                                v-for="(type, key, index) in nodes"
                                :key="index"
                                class="card"
                            >
                                <div class="card-body">
                                    <div
                                        v-show="group.items.length > 0"
                                        v-for="group in type.items"
                                        :key="type.name + group.name"
                                        :class="{
                                            'full-width': group.collapsible,
                                            'half-width': !group.collapsible
                                        }"
                                    >
                                        <div
                                            class="name"
                                            @click="
                                                toggleLayer(
                                                    type.name + '|' + group.name
                                                )
                                            "
                                            :class="{
                                                'map-hidden': isLayerHidden(
                                                    type.name + '|' + group.name
                                                )
                                            }"
                                        >
                                            <img
                                                :src="
                                                    '/img/map-icons/' +
                                                        group.icon +
                                                        '.png'
                                                "
                                                :alt="group.name + ' Icon'"
                                                class="img-fluid"
                                            />
                                            <span>{{ lang('map.groups.' + group.name, group.name) }}</span>
                                        </div>
                                        <div
                                            v-if="group.collapsible"
                                            class="visibility-toggle collapsed"
                                            data-toggle="collapse"
                                            :data-target="
                                                '#collapsible-' +
                                                    collapsible(type, group)
                                            "
                                        >
                                            <i class="fas fa-caret-up"></i>
                                            <i class="fas fa-caret-down"></i>
                                        </div>
                                        <div
                                            v-if="group.collapsible"
                                            class="collapsible-items collapse"
                                            :id="
                                                'collapsible-' +
                                                    collapsible(type, group)
                                            "
                                        >
                                            <ul
                                                :style="{
                                                    columns:
                                                        group.items.length > 1
                                                            ? 2
                                                            : 1
                                                }"
                                            >
                                                <li
                                                    v-for="node in noDuplicates(
                                                        group.items
                                                    )"
                                                    :key="node.name"
                                                >
                                                    <img
                                                        v-if="
                                                            node.icon !=
                                                                group.icon
                                                        "
                                                        :src="
                                                            '/img/map-icons/' +
                                                                node.icon +
                                                                '.png'
                                                        "
                                                        :alt="
                                                            node.name + ' Icon'
                                                        "
                                                        class="img-fluid"
                                                    />
                                                    {{ node.name }}
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-else>
                            <div
                                class="card"
                                v-for="(type, key, index) in nodes"
                                :key="index"
                            >
                                <div
                                    class="card-header"
                                    :id="'header-' + index"
                                >
                                    <div
                                        class="name collapsed"
                                        data-toggle="collapse"
                                        :data-target="'#body-' + index"
                                        aria-expanded="false"
                                        :aria-controls="'body-' + index"
                                    >
                                        {{ lang('map.types.' + type.name, type.name) }}
                                        <span class="float-right">
                                            <i class="fas fa-caret-down"></i>
                                            <i class="fas fa-caret-up"></i>
                                        </span>
                                    </div>
                                    <div
                                        class="visibility-toggle group-toggle"
                                        @click="toggleLayer(type.name + '|*')"
                                        :class="{
                                            'map-hidden': isLayerHidden(
                                                type.name + '|*'
                                            )
                                        }"
                                    >
                                        <i class="far fa-eye"></i>
                                        <i class="far fa-eye-slash"></i>
                                    </div>
                                </div>
                                <div
                                    :id="'body-' + index"
                                    class="collapse"
                                    :aria-labelledby="'header-' + index"
                                >
                                    <div class="card-body">
                                        <div
                                            v-for="group in type.items"
                                            :key="type.name + group.name"
                                            :class="{
                                                'full-width': group.collapsible,
                                                'half-width': !group.collapsible
                                            }"
                                        >
                                            <div
                                                class="name"
                                                @click="
                                                    toggleLayer(
                                                        type.name +
                                                            '|' +
                                                            group.name
                                                    )
                                                "
                                                :class="{
                                                    'map-hidden': isLayerHidden(
                                                        type.name +
                                                            '|' +
                                                            group.name
                                                    )
                                                }"
                                            >
                                                <img
                                                    :src="
                                                        '/img/map-icons/' +
                                                            group.icon +
                                                            '.png'
                                                    "
                                                    :alt="group.name + ' Icon'"
                                                    class="img-fluid"
                                                />
                                                <span>{{ lang('map.groups.' + type.name + '|' + group.name, group.name) }}</span>
                                            </div>
                                            <div
                                                v-if="group.collapsible"
                                                class="visibility-toggle collapsed"
                                                data-toggle="collapse"
                                                :data-target="
                                                    '#collapsible-' +
                                                        collapsible(type, group)
                                                "
                                            >
                                                <i class="fas fa-caret-up"></i>
                                                <i
                                                    class="fas fa-caret-down"
                                                ></i>
                                            </div>
                                            <div
                                                v-if="group.collapsible"
                                                class="collapsible-items collapse"
                                                :id="
                                                    'collapsible-' +
                                                        collapsible(type, group)
                                                "
                                            >
                                                <ul
                                                    :style="{
                                                        columns:
                                                            group.items.length >
                                                            1
                                                                ? 2
                                                                : 1
                                                    }"
                                                >
                                                    <li
                                                        v-for="node in noDuplicates(
                                                            group.items
                                                        )"
                                                        :key="node.name"
                                                    >
                                                        <img
                                                            v-if="
                                                                node.icon !=
                                                                    group.icon
                                                            "
                                                            :src="
                                                                '/img/map-icons/' +
                                                                    node.icon +
                                                                    '.png'
                                                            "
                                                            :alt="
                                                                node.name +
                                                                    ' Icon'
                                                            "
                                                            class="img-fluid"
                                                        />
                                                        {{ node.name }}
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div
                        class="edit-menu"
                        v-show="editor.enabled && editor.mode === ''"
                    >
                        <h3>{{ $t('map.what-would-you-like-to-do') }}</h3>
                        <div class="editor-button" @click="editorMenu('items')">
                            <h3>
                                <i class="fas fa-fw fa-map-marker-alt"></i>
                                {{ $t('map.add-remove-items') }}
                            </h3>
                        </div>
                        <div
                            v-if="mission.missionType != 'Sniper Assassin'"
                            class="editor-button"
                            @click="editorMenu('ledges')"
                        >
                            <h3>
                                <i class="fas fa-fw fa-bezier-curve"></i>
                                {{ $t('map.add-remove-ledges') }}
                            </h3>
                        </div>
                        <div
                            v-if="mission.missionType != 'Sniper Assassin'"
                            class="editor-button"
                            @click="editorMenu('foliage')"
                        >
                            <h3>
                                <i class="fas fa-fw fa-leaf"></i>
                                {{ $t('map.add-remove-foliage') }}
                            </h3>
                        </div>
                        <div
                            v-if="mission.missionType != 'Sniper Assassin'"
                            class="editor-button"
                            @click="editorMenu('disguises')"
                        >
                            <h3>
                                <i class="fas fa-fw fa-user-tie"></i>
                                {{ $t('map.manage-disguise-areas') }}
                            </h3>
                        </div>
                        <i18n path="map.click-icon-to-close" tag="p">
                            <span slot="pencilIcon">
                                <i class="fas fa-pencil-alt"></i>
                            </span>
                        </i18n>
                    </div>
                    <div
                        class="items-menu"
                        v-show="editor.enabled && editor.mode === 'items'"
                    >
                        <p>
                            <i class="fas fa-fw fa-plus-circle"></i>
                            {{ $t('map.click-to-add') }}
                        </p>
                        <p>
                            <i class="fas fa-fw fa-arrows-alt"></i>
                            {{ $t('map.drag-to-move') }}
                        </p>
                        <p>
                            <i class="fas fa-fw fa-trash"></i>
                            {{ $t('map.click-item-to-delete') }}
                        </p>
                        <div class="editor-button" @click="editorMenu('')">
                            <h3>
                                <i class="fas fa-times-circle"></i>
                                {{ $t('map.close-item-menu') }}
                            </h3>
                        </div>
                    </div>
                    <div
                        class="ledges-menu"
                        v-show="editor.enabled && editor.mode === 'ledges'"
                    >
                        <p data-ledge="delete-help">
                            <i class="fas fa-trash"></i>
                            {{ $t('map.click-ledge-to-delete') }}
                        </p>
                        <div class="editor-button"
                             :class="{'selected': editor.polyActive}"
                             @click="toggleDraw('Line')">
                            <h3>
                                <i class="fas fa-plus-circle"></i>
                                {{ $t('map.add-ledge') }}
                            </h3>
                            <p>{{ $t('map.toggle-ledge-builder') }}</p>
                        </div>
                        <div
                            class="editor-button"
                            @click="
                                editorMenu('')
                                map.pm.disableDraw('Line')
                            "
                        >
                            <h3>
                                <i class="fas fa-times-circle"></i>
                                {{ $t('map.close-ledge-menu') }}
                            </h3>
                        </div>
                    </div>
                    <div
                        class="foliage-menu"
                        v-show="editor.enabled && editor.mode === 'foliage'"
                    >
                        <p data-foliage="delete-help">
                            <i class="fas fa-trash"></i>
                            Click on an existing foliage to delete it.
                        </p>
                        <div
                            class="editor-button"
                            :class="{'selected': editor.polyActive}"
                            @click="toggleDraw('Polygon')"
                        >
                            <h3>
                                <i class="fas fa-plus-circle"></i>
                                {{ $t('map.add-foliage') }}
                            </h3>
                            <p>
                                {{ $t('map.toggle-foliage-builder') }}
                            </p>
                        </div>
                        <div
                            class="editor-button"
                            @click="
                                editorMenu('')
                                map.pm.disableDraw('Polygon')
                            "
                        >
                            <h3>
                                <i class="fas fa-times-circle"></i>
                                {{ $t('map.close-foliage-menu') }}
                            </h3>
                        </div>
                    </div>
                    <div
                        class="disguise-trespassing-menu"
                        v-show="editor.enabled && editor.mode === 'disguises'"
                    >
                        <p data-disguise="delete-help">
                            <i class="fas fa-trash"></i>
                            {{ $t('map.delete-existing-region') }}
                        </p>
                        <p>{{ $t('map.select-disguise-for-editing') }}</p>
                        <div class="search-box">
                            <select
                                ref="disguisePicker"
                                @change="partialChangeDisguise($event.target.value)"
                                name="disguise-menu-dropdown"
                                class="selectpicker"
                                data-style="control-button"
                            >
                                <option value="NONE">None</option>
                                <option
                                    v-for="disguise in disguises"
                                    :key="disguise.id"
                                    :value="disguise.id"
                                >
                                    {{ disguise.name }}
                                </option>
                            </select>
                        </div>
                        <div
                            class="editor-button"
                            data-disguise="add"
                            data-type="trespassing"
                            :class="{'selected': editor.polyActive && editor.disguiseType === 'trespassing'}"
                            @click="
                                toggleDraw('Polygon')
                                editor.disguiseType = 'trespassing'
                            "
                            v-if="editor.currentDisguise != 'NONE'"
                        >
                            <h3>
                                <i class="fas fa-plus-circle"></i>
                                {{ $t('map.add-trespassing-region') }}
                            </h3>
                            <p>{{ $t('map.toggle-trespassing-builder') }}</p>
                        </div>
                        <div
                            class="editor-button"
                            data-disguise="add"
                            data-type="hostile"
                            :class="{'selected': editor.polyActive && editor.disguiseType === 'hostile'}"
                            @click="
                                toggleDraw('Polygon')
                                editor.disguiseType = 'hostile'
                            "
                            v-if="editor.currentDisguise != 'NONE'"
                        >
                            <h3>
                                <i class="fas fa-plus-circle"></i>
                                {{ $t('map.add-hostile-region') }}
                            </h3>
                            <p>{{ $t('map.toggle-hostile-builder') }}</p>
                        </div>
                        <div
                            class="editor-button"
                            data-disguise="copy"
                            v-if="editor.currentDisguise === 'NONE'"
                            @click="showCopyDisguiseModal"
                        >
                            <h3>
                                <i class="fas fa-copy"></i>
                                {{ $t('map.copy-regions') }}
                            </h3>
                            <p>{{ $t('map.click-to-copy-regions') }}</p>
                        </div>
                        <div
                            class="editor-button"
                            @click="
                                editorMenu('')
                                map.pm.disableDraw('Polygon')
                            "
                        >
                            <h3>
                                <i class="fas fa-times-circle"></i>
                                {{ $t('map.close-disguise-menu') }}
                            </h3>
                        </div>
                    </div>
                </div>
            </nav>
            <modal
                modal-title="Copy Disguise Regions"
                id="copy-disguises-modal"
                tabindex="-1"
                role="dialog"
            >
                <div class="alert alert-warning">
                    {{ $t('map.copy-disguises-warning') }}
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="original-disguise">
                                {{ $t('map.source-disguise') }}
                            </label>
                            <br />
                            <select
                                id="original-disguise"
                                name="original-disguise"
                                class="selectpicker"
                                v-model="copyDisguiseArea.source"
                                required
                            >
                                <option disabled selected>
                                    {{ $t('form.select') }}
                                </option>
                                <option
                                    v-for="disguise in disguises"
                                    :value="disguise.id"
                                >
                                    {{ disguise.name }}
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="target-disguise">
                                {{ $t('map.target-disguise') }}
                            </label>
                            <br />
                            <select
                                id="target-disguise"
                                name="target-disguise"
                                class="selectpicker"
                                v-model="copyDisguiseArea.destination"
                                required
                            >
                                <option disabled selected>
                                    {{ $t('form.select') }}
                                </option>
                                <option
                                    v-for="disguise in disguises"
                                    :value="disguise.id"
                                >
                                    {{ disguise.name }}
                                </option>
                            </select>
                        </div>
                    </div>
                </div>
                <template v-slot:modal-footer>
                    <game-button
                        type="submit"
                        id="copy-disguise-regions-btn"
                        @click="copyDisguiseAreas"
                    >
                        <img
                            src="/img/game-icons/modal-continue.png"
                            class="normal img-fluid"
                            :alt="$t('form.submit-icon')"
                        />
                        <img
                            src="/img/game-icons/modal-continue-inverted.png"
                            class="inverted img-fluid"
                            :alt="$t('form.submit-icon')"
                        />
                        {{ $t('form.copy') }}
                    </game-button>
                    <game-button type="button" data-dismiss="modal">
                        <img
                            src="/img/game-icons/modal-close.png"
                            class="normal img-fluid"
                            :alt="$t('form.cancel-icon')"
                        />
                        <img
                            src="/img/game-icons/modal-close-inverted.png"
                            class="inverted img-fluid"
                            :alt="$t('form.cancel-icon')"
                        />
                        {{ $t('form.cancel') }}
                    </game-button>
                </template>
            </modal>
            <modal :modal-title="$t('map.confirm-move')"
                   id="confirm-move-modal"
                   tabindex="-1"
                   role="dialog"
                   dismissable>
                <div class="alert alert-warning">
                    {{ $t('map.confirm-reposition') }}
                </div>
                <template v-slot:modal-footer>
                    <game-button type="button" @click="cancelMoveMarker" data-dismiss="modal">
                        <img
                                src="/img/game-icons/modal-close.png"
                                class="normal img-fluid"
                                :alt="$t('form.cancel-icon')"
                        />
                        <img
                                src="/img/game-icons/modal-close-inverted.png"
                                class="inverted img-fluid"
                                :alt="$t('form.cancel-icon')"
                        />
                        {{ $t('form.cancel') }}
                    </game-button>
                    <game-button
                            type="submit"
                            @click="confirmMove"
                    >
                        <img
                                src="/img/game-icons/modal-continue.png"
                                class="normal img-fluid"
                                :alt="$t('form.submit-icon')"
                        />
                        <img
                                src="/img/game-icons/modal-continue-inverted.png"
                                class="inverted img-fluid"
                                :alt="$t('form.submit-icon')"
                        />
                        {{ $t('form.save') }}
                    </game-button>
                </template>
            </modal>
            <modal :modal-title="$t('map.add-edit-item')"
                   id="editModal"
                   tabindex="-1"
                   role="dialog"
                   dismissable>
                <div
                        v-show="
                                    mission.missionType != 'Sniper Assassin'
                                "
                >
                    <h3>{{ $t('map.apply-template') }}</h3>
                    <div class="form-group row">
                        <label
                                for="template"
                                class="col sm-2 col-form-label"
                        >
                            {{ $t('map.template') }}
                        </label>
                        <div class="col-sm-10">
                            <select
                                    @change="applyTemplate"
                                    name="template"
                                    ref="templatePicker"
                                    class="form-control selectpicker"
                                    title="Select One"
                                    data-live-search="true"
                            >
                                <optgroup
                                        v-for="(group,
                                                key) in editor.templates"
                                        :key="key"
                                        :label="key"
                                >
                                    <option
                                            v-for="item in group"
                                            :key="item.id"
                                            :value="key + '|' + item.id"
                                    >
                                        {{ item.name }}
                                    </option>
                                </optgroup>
                            </select>
                        </div>
                    </div>
                    <hr />
                </div>
                <h3>Item Info</h3>
                <div
                        class="alert alert-dark"
                        style="font-size: .8em"
                >
                    {{ $t('map.please-indicate-when-adding') }}
                    <ul>
                        <li>{{ $t('map.required-items') }}</li>
                        <li>{{ $t('map.suspicious-items') }}</li>
                        <li>{{ $t('map.information-items') }}</li>
                        <li>{{ $t('map.in-game-description-items') }}</li>
                        <li>{{ $t('map.blank-notes') }}</li>
                    </ul>
                </div>
                <div class="form-group row">
                    <label
                            for="subgroup"
                            class="col-sm-2 col-form-label"
                    >
                        {{ $t('map.category') }}
                    </label>
                    <div class="col-sm-10">
                        <select
                                @change="selectSubgroup"
                                name="subgroup"
                                ref="subgroupPicker"
                                class="form-control selectpicker"
                                title="Select One"
                                data-live-search="true"
                        >
                            <optgroup
                                    v-for="(category,
                                            key) in categories"
                                    :key="key"
                                    :label="key"
                            >
                                <option
                                        v-for="group in category"
                                        :key="group.id"
                                        :value="
                                                    key + '|' + group.subgroup
                                                "
                                        :data-content="
                                                    `<img height='24' width='24' src='/img/map-icons/` +
                                                        group.icon +
                                                        `.png' alt='` +
                                                        group.group +
                                                        ` Icon'> ` +
                                                        group.group
                                                "
                                >
                                    {{ group.group }}
                                </option>
                            </optgroup>
                        </select>
                        <small
                                v-if="editor.currentCategory"
                                class="form-text text-muted"
                                id="note-help-text"
                        >
                            {{ currentCategory.note }}
                        </small>
                    </div>
                </div>
                <div
                        v-show="pickIconAllowed"
                        class="form-group row"
                        id="icon-form-group"
                >
                    <label
                            for="icon"
                            class="col-sm-2 col-form-label"
                    >
                        {{ $t('map.icon') }}
                    </label>
                    <div class="col-sm-10">
                        <select
                                name="icon"
                                @change="selectIcon"
                                ref="iconPicker"
                                class="form-control selectpicker"
                                data-live-search="true"
                        >
                            <optgroup
                                    v-for="(group, key) in editor.icons"
                                    :key="key"
                                    :label="key"
                            >
                                <option
                                        v-for="icon in group"
                                        :key="icon.id"
                                        :value="icon.icon"
                                        :data-content="
                                                    `<img height='24' width='24' src='/img/map-icons/` +
                                                        icon.icon +
                                                        `.png' alt='` +
                                                        icon.altText +
                                                        ` Icon'> ` +
                                                        icon.altText
                                                "
                                >
                                    {{ icon.altText }}
                                </option>
                            </optgroup>
                        </select>
                    </div>
                </div>
                <div v-if="editor.currentCategory">
                    <div
                            class="form-group row"
                            v-if="currentCategory.requireName"
                    >
                        <label
                                for="name"
                                class="col-sm-2 col-form-label"
                        >
                            {{ $t('map.name') }}
                        </label>
                        <div class="col-sm-10">
                                        <textarea
                                                type="text"
                                                name="name"
                                                v-model="currentCategory.name"
                                                class="form-control"
                                        ></textarea>
                            <small class="form-text text-muted">
                                {{ $t('map.name-note') }}
                            </small>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="quantity" class="col-sm-2 col-form-label">
                            {{ $t('map.quantity') }}
                        </label>
                        <div class="col-sm-10">
                            <select name="quantity"
                                    v-model="currentCategory.defaultQuantity"
                                    class="form-control">
                                <option v-for="n in 10" :value="n">
                                    {{ n }}
                                </option>
                            </select>
                        </div>
                    </div>
                    <div
                            class="form-group row"
                            v-if="currentCategory.requireAction"
                    >
                        <label
                                for="action"
                                class="col-sm-2 col-form-label"
                        >
                            {{ $t('map.action') }}
                        </label>
                        <div class="col-sm-10">
                            <input
                                    type="text"
                                    name="action"
                                    v-model="currentCategory.action"
                                    class="form-control"
                            />
                            <small class="form-text text-muted">
                                {{ $t('map.action-note') }}
                            </small>
                        </div>
                    </div>
                    <div
                            class="form-group row"
                            v-if="currentCategory.requireTarget"
                    >
                        <label
                                for="target"
                                class="col-sm-2 col-form-label"
                        >
                            {{ $t('map.target') }}
                        </label>
                        <div class="col-sm-10">
                            <input
                                    type="text"
                                    name="target"
                                    v-model="currentCategory.target"
                                    class="form-control"
                            />
                            <small class="form-text text-muted">
                                {{ $t('map.target-note') }}
                            </small>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="image"
                               class="col-sm-2 col-form-label">
                            {{ $t('map.image-url') }}
                        </label>
                        <div class="col-sm-10">
                            <input type="text"
                                   name="image"
                                   v-model="currentCategory.image"
                                   class="form-control">
                            <small class="form-text text-muted">
                                {{ $t('map.image-url-note') }}
                            </small>
                        </div>
                    </div>
                    <div
                            class="form-group row"
                            v-if="currentCategory.requirePickup"
                    >
                        <label
                                for="pickup-type"
                                class="col-sm-2 col-form-label"
                        >
                            {{ $t('map.type') }}
                        </label>
                        <div class="col-sm-10">
                            <div class="form-check">
                                <input
                                        class="form-check-input"
                                        type="radio"
                                        v-model="
                                                    currentCategory.pickupType
                                                "
                                        name="pickup-type"
                                        value="large"
                                        checked
                                />
                                <label
                                        class="form-check-label"
                                        for="large"
                                >
                                    {{ $t('map.pickup-large') }}
                                </label>
                            </div>
                            <div class="form-check">
                                <input
                                        class="form-check-input"
                                        type="radio"
                                        v-model="
                                                    currentCategory.pickupType
                                                "
                                        name="pickup-type"
                                        value="small"
                                />
                                <label
                                        class="form-check-label"
                                        for="small"
                                >
                                    {{ $t('map.stash-small') }}
                                </label>
                            </div>
                        </div>
                    </div>
                    <div
                            class="form-group row"
                            v-if="currentCategory.requireDirection"
                    >
                        <label
                                for="stairwell-direction"
                                class="col-sm-2 col-form-label"
                        >
                            {{ $t('map.direction') }}
                        </label>
                        <div class="col-sm-10">
                            <div class="form-check">
                                <input
                                        class="form-check-input"
                                        type="radio"
                                        v-model="
                                                    currentCategory.stairwellDirection
                                                "
                                        name="stairwell-direction"
                                        value="up-stair"
                                        checked
                                />
                                <label
                                        class="form-check-label"
                                        for="up-stair"
                                >
                                    {{ $t('map.direction-up') }}
                                </label>
                            </div>
                            <div class="form-check">
                                <input
                                        class="form-check-input"
                                        type="radio"
                                        v-model="
                                                    currentCategory.stairwellDirection
                                                "
                                        name="stairwell-direction"
                                        value="up-down-stair"
                                />
                                <label
                                        class="form-check-label"
                                        for="up-down-stair"
                                >
                                    {{ $t('map.direction-up-down') }}
                                </label>
                            </div>
                            <div class="form-check">
                                <input
                                        class="form-check-input"
                                        type="radio"
                                        v-model="
                                                    currentCategory.stairwellDirection
                                                "
                                        name="stairwell-direction"
                                        value="down-stair"
                                />
                                <label
                                        class="form-check-label"
                                        for="down-stair"
                                >
                                    {{ $t('map.direction-down') }}
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <h3>{{ $t('map.notes') }}</h3>
                <div id="suggest-notes">
                    <div
                            v-for="note in editor.notes"
                            :key="note.type"
                    >
                        <div class="form-group row">
                            <label
                                    for="note-type[]"
                                    class="col-sm-2 col-form-label"
                            >
                                {{ $t('map.type') }}
                            </label>
                            <div class="col-sm-10">
                                <select
                                        v-model="note.type"
                                        class="form-control"
                                        name="note-type[]"
                                >
                                    <option value="requirement">
                                        {{ $t('map.requirement') }}
                                    </option>
                                    <option value="warning">
                                        {{ $t('map.warning') }}
                                    </option>
                                    <option value="info">
                                        {{ $t('map.information') }}
                                    </option>
                                    <option value="description">
                                        {{ $t('map.description') }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label
                                    for="note-text[]"
                                    class="col-sm-2 col-form-label"
                            >
                                {{ $t('map.text') }}
                            </label>
                            <div class="col-sm-10">
                                <input
                                        type="text"
                                        name="note-text[]"
                                        v-model="note.text"
                                        class="form-control"
                                />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-8 offset-sm-2">
                        <button
                                type="button"
                                @click="editor.notes.push({})"
                                id="add-note-button"
                                class="btn btn-dark"
                        >
                            <i class="fas fa-plus-circle"></i>
                            {{ $t('map.add-another-note') }}
                        </button>
                    </div>
                </div>
                <template v-slot:modal-footer>
                    <game-button type="button" data-dismiss="modal">
                        <img
                                src="/img/game-icons/modal-close.png"
                                class="normal img-fluid"
                                :alt="$t('form.cancel-icon')"
                        />
                        <img
                                src="/img/game-icons/modal-close-inverted.png"
                                class="inverted img-fluid"
                                :alt="$t('form.cancel-icon')"
                        />
                        {{ $t('form.close') }}
                    </game-button>
                    <game-button
                            type="submit"
                            @click="saveMarker"
                    >
                        <img
                                src="/img/game-icons/modal-continue.png"
                                class="normal img-fluid"
                                :alt="$t('form.submit-icon')"
                        />
                        <img
                                src="/img/game-icons/modal-continue-inverted.png"
                                class="inverted img-fluid"
                                :alt="$t('form.submit-icon')"
                        />
                        {{ $t('form.save') }}
                    </game-button>
                </template>
            </modal>
        </div>
        <script type="text/html" id="popup-template">
            <div>
                <img src="#" alt="Image template holder">
                <div data-name="name">Stove</div>
                <div data-name="group">Explosion</div>
                <div data-name="target">
                    <i class="far"></i>
                    <span>Start Gas Leak</span>
                </div>
                <div data-name="notes"></div>
                <div data-authenticated-only>
                    <button class="btn btn-danger btn-sm" data-action="delete-btn" data-node-id="x" data-toggle="tooltip" title="Delete">
                        <i class="fas fa-times"></i>
                    </button>
                    <button class="btn btn-warning btn-sm" data-action="edit-btn" data-node-id="x" data-toggle="tooltip" title="Edit">
                        <i class="fas fa-pencil-alt"></i>
                    </button>
                </div>
            </div>
        </script>
        <script type="text/html" id="popup-note-template" aria-hidden="true">
            <div>
                <div>
                    <div class="in-game-description">{{ $t('map.in-game-description') }}</div>
                    <div data-name="note-contents"></div>
                </div>
            </div>
        </script>
    </div>
</template>

<script>
import Vue from 'vue'

import CxltToaster from 'cxlt-vue2-toastr'
import 'cxlt-vue2-toastr/dist/css/cxlt-vue2-toastr.css'

import GameButton from '../components/GameButton'
import Modal from '../components/Modal'

import LanguageHelpers from "../components/LanguageHelpers";

Vue.use(CxltToaster)
export default {
    name: 'map-view',
    components: {
        GameButton,
        Modal
    },
    title() {
        return this.mission ? this.lang('missions.' + this.mission.slug, this.mission.name) : 'Loading'
    },
    data() {
        return {
            disguises: [],
            ledges: [],
            foliage: [],
            nodes: null,
            searchableNodes: null,
            currentLayer: 0,
            overlays: [],
            layerGroups: [],
            map: null,
            mapLayers: [],
            categories: {},
            mapLoaded: false,
            hiddenLayers: [],
            searchedItem: null,
            floorsWithSearchResults: [],
            floorCountOverride: [],
            editor: {
                enabled: false,
                mode: '',
                templates: null,
                icons: null,
                currentCategory: null,
                currentDisguise: 'NONE',
                disguiseType: null,
                notes: [],
                clickedPoint: {},
                currentMarker: {},
                originalLatLng: null,
                vertices: [],
                workingLayers: [],
                polyActive: false,
                currentMarkerNode: null
            },
            copyDisguiseArea: {
                source: -1,
                destination: -1
            }
        }
    },
    computed: {
        mission: function() {
            return this.$store.state.mission
        },
        mapUrl: function() {
            return (
                this.$domain +
                '/api/maps/' +
                this.mission.mapFolderName +
                '/tiles/'
            )
        },
        loadingTile: function() {
            if (this.mission.missionType !== 'Elusive Target') {
                return this.mission.tileUrl;
            }

            if (this.game != null && this.game.slug == 'hitman') {
                return (
                    '/img/jpg/elusive-targets/legacy/' + this.mission.slug + '.jpg'
                )
            }

            return '/img/jpg/elusive-targets/' + this.mission.slug + '.jpg'
        },
        game: function() {
            return this.$store.state.game
        },
        currentCategory: function() {
            if (
                this.editor.currentCategory == '' ||
                this.editor.currentCategory === null ||
                this.editor.currentCategory === undefined
            )
                return
            var split = this.editor.currentCategory.split('|')
            return this.categories[split[0]].find(
                element => element.subgroup == split[1]
            )
        },
        currentDisguise: function() {
            if (this.editor.currentDisguise === 'NONE') return null
            return this.disguises.find(
                element => element.id == this.editor.currentDisguise
            )
        },
        pickIconAllowed: function() {
            if (!this.editor.currentCategory) return true
            return (
                [
                    'Navigation|agency-pickup',
                    'Navigation|exit-location',
                    'Navigation|ledge',
                    'Navigation|foliage',
                    'Navigation|up-stair',
                    'Navigation|starting-location',
                    'Navigation|up-pipe'
                ].indexOf(this.editor.currentCategory) == -1
            )
        },
        isLoggedIn: function() {
            if (localStorage.getItem('token') != null) {
                var data = JSON.parse(
                    atob(localStorage.getItem('token').split('.')[1])
                )
                var now = new Date()
                if (new Date(data.exp * 1000).getTime() - now.getTime() > 0)
                    return true
            }
            return false
        }
    },
    methods: {
        getCountryFlag: function() {
            return LanguageHelpers.getCountryFlagForLocale(this.$i18n);
        },
        logout: function() {
            localStorage.removeItem('token');
            location.reload();
        },
        noDuplicates: function(val) {
            var filtered_array = []
            for (var i = 0; i < val.length; i++) {
                if (
                    filtered_array.findIndex(el => el.name === val[i].name) ===
                    -1
                ) {
                    filtered_array.push(val[i])
                }
            }
            return filtered_array
        },
        range: function(min, max) {
            var array = [],
                j = 0
            for (var i = min; i <= max; i++) {
                array[j] = i
                j++
            }
            return array
        },
        changeLevel: function(level) {
            this.map.removeLayer(this.mapLayers[this.currentLayer]);
            this.currentLayer = level;
            this.map.addLayer(this.mapLayers[this.currentLayer]);
            this.updateNodeLayerState();
        },
        collapsible: function(type, group) {
            return (
                type.name.replace(/ /g, '_') +
                '-' +
                group.name.replace(/ /g, '_')
            )
        },
        editorMenu: function(menu) {
            this.editor.mode = menu;
            if (menu === '') {
                this.toggleDraw('ALL');
            }
            this.updateNodeLayerState();
        },
        buildMarker: function(node) {
            const that = this;
            const icon = node.icon === 'area' ?
                new L.DivIcon({
                    className: 'area-icon',
                    html: node.name.replace(/(?:\r\n|\r|\n)/g, '<br>')
                }) :
                L.icon({iconUrl: '/img/map-icons/' + node.icon + '.png',
                    iconSize: [32, 32],
                    iconAnchor: [16, 16],
                    popupAnchor: [0, 0]
                });
            return L.marker([node.latitude, node.longitude], {
                icon: icon,
                custom: {
                    id: node.id,
                    node: node
                },
                riseOnHover: true
            }).on('click', function(e) {
                that.editor.currentMarker = node;
            }).on('dragend', this.moveMarker);
        },
        buildPopup: function(element) {
            let node = element.options.custom.node;

            let $template = $($('#popup-template').html());
            $template.find('[data-name="group"]').html(node.group).end()
                .find('[data-name="name"]').html(node.name).end()
                .find('[data-node-id="x"]').attr('data-node-id', node.id).end()
                .find('[data-name="target"]').find('span').html(node.target);

            if (node.target !== null && node.target !== '' && node.targetIcon !== '') {
                $template.find('[data-name="target"]').find('i').addClass(node.targetIcon).show();
            } else {
                $template.find('[data-name="target"]').find('i').hide();
            }

            if (node.image !== null) {
                $template.find('img').attr('src', node.image);
            } else {
                $template.find('img').remove();
            }

            for (let i in node.notes) {
                let $noteTemplate = $($('#popup-note-template').html());

                $noteTemplate.find('[data-name="note-contents"]').html(node.notes[i].text).parent().addClass(node.notes[i].type);

                $template.find('[data-name="notes"]').append($noteTemplate.html());
            }

            if (!this.isLoggedIn) {
                // Yeah, this isn't the most "secure" thing... but the editor won't work unless you're authenticated.
                // Additionally, anyone can create an account, so it's not confidential information.
                $template.find('[data-authenticated-only]').html('');
            }

            return $template.html();
        },
        buildLedge: function(ledge) {
            const that = this;
            const polyline = L.polyline(this.parseCoords(ledge.vertices), {
                color: '#fff',
                weight: 4,
                opacity: .75,
                custom: {
                    id: ledge.id
                }
            }).on('click', function() {
                that.editor.currentMarker = this;
                that.deletePoly(ledge, 'ledges');
            });

            return polyline.bindTooltip('Ledge', {sticky: true});
        },
        buildFoliage: function(foliage) {
            const that = this;
            const polygon = L.polygon(this.parseCoords(foliage.vertices), {
                color: '#248f24',
                weight: 4,
                opacity: .75,
                custom: {
                    id: foliage.id
                }
            }).on('click', function() {
                that.deletePoly(foliage, 'foliage');
            });

            return polygon.bindTooltip('Foliage', {sticky: true});
        },
        buildDisguiseArea: function(disguiseArea) {
            const that = this;
            const polygon = L.polygon(this.parseCoords(disguiseArea.vertices), {
                color: disguiseArea.type === 'trespassing' ? 'yellow' : '#f00',
                stroke: false,
                weight: 4,
                opacity: .75,
                custom: {
                    id: disguiseArea.id
                }
            }).on('click', function() {
                that.deletePoly(disguiseArea, 'disguise-areas')
            });

            const tooltip = disguiseArea.type === 'trespassing' ? 'Trespassing' : 'Hostile Area';
            return polygon.bindTooltip(tooltip, {sticky: true});
        },
        toggleEditor: function() {
            if (this.editor.enabled) {
                this.toggleDraw('ALL');
            }

            this.editor.enabled = !this.editor.enabled;
        },
        addMarker: function(event) {
            if (!this.editor.enabled || this.editor.mode !== 'items') return
            this.editor.clickedPoint = event.latlng
            let element = '#editModal'
            //Reset old data
            if (this.currentCategory) {
                this.currentCategory.nodeId = undefined
                this.currentCategory.name = undefined
                this.currentCategory.defaultQuantity = 1
                this.currentCategory.action = undefined
                this.currentCategory.target = undefined
                this.currentCategory.image = undefined
            }

            this.editor.currentCategory = undefined
            $(this.$refs.subgroupPicker).selectpicker('val', -1)
            $(this.$refs.iconPicker).selectpicker('val', -1)
            $(this.$refs.templatePicker).selectpicker('val', -1)
            this.editor.notes = []
            $(element).modal('show')
        },
        moveMarker: function(event) {
            let item = event.target;
            item.latitude = event.target.getLatLng().lat
            item.longitude = event.target.getLatLng().lng
            this.editor.currentMarker = item
            this.editor.currentMarkerNode = item.options.custom.node;
            this.editor.originalLatLng = [item.options.custom.node.latitude, item.options.custom.node.longitude];
            $('#confirm-move-modal').modal('show')
        },
        cancelMoveMarker: function() {
            this.editor.currentMarker.setLatLng(this.editor.originalLatLng);
        },
        editMarker: function() {
            const item = this.editor.currentMarker;
            this.editor.notes = item.notes
            this.editor.currentCategory = item.type + '|' + item.subgroup
            this.editor.clickedPoint = L.latLng(item.latitude, item.longitude)
            this.currentCategory.icon = item.icon;
            $(this.$refs.subgroupPicker).selectpicker(
                'val',
                item.type + '|' + item.subgroup
            )
            $(this.$refs.iconPicker).selectpicker(
                'val',
                this.currentCategory.icon
            )
            this.currentCategory.nodeId = item.id
            this.currentCategory.name = item.name
            this.currentCategory.defaultQuantity = item.quantity
            this.currentCategory.action = item.target
            this.currentCategory.target = item.target
            this.currentCategory.image = item.image
            this.currentCategory.pickupType = item.pickupType

            $('#editModal').modal('show')
        },
        confirmMove: function() {
            var data = new FormData()
            data.append('node-id', this.editor.currentMarkerNode.id)
            data.append('latitude', this.editor.currentMarker.latitude)
            data.append('longitude', this.editor.currentMarker.longitude)
            this.$request(true, 'nodes/move', data).then(() => {
                    this.$toast.success({
                        message: 'Item moved!'
                    });
                    $('#confirm-move-modal').modal('hide')
                }
            )
        },
        selectSubgroup: function(event) {
            if (event.target.value == '') return
            this.editor.currentCategory = event.target.value
            $(this.$refs.iconPicker).selectpicker(
                'val',
                this.currentCategory.icon
            )
        },
        selectIcon: function(event) {
            if (event.target.value == '') return
            this.currentCategory.icon = event.target.value
        },
        applyTemplate: function(event) {
            if (event.target.value == '') return
            var split = event.target.value.split('|')
            var currentTemplate = this.editor.templates[split[0]].find(
                element => element.id == split[1]
            )
            $(this.$refs.subgroupPicker).selectpicker(
                'val',
                currentTemplate.type + '|' + currentTemplate.subgroup
            )
            this.$refs.subgroupPicker.dispatchEvent(new Event('change'))
            $(this.$refs.iconPicker).selectpicker('val', currentTemplate.icon)
            //Dunno if this is needed
            this.$refs.iconPicker.dispatchEvent(new Event('change'))

            this.currentCategory.name = currentTemplate.name
            this.currentCategory.action = currentTemplate.target
            this.currentCategory.target = currentTemplate.target
            this.currentCategory.defaultQuantity = 1
            this.currentCategory.image = currentTemplate.image

            if (currentTemplate.description)
                this.editor.notes.push({
                    type: 'description',
                    text: currentTemplate.description
                })
            if (currentTemplate.requirement)
                this.editor.notes.push({
                    type: 'requirement',
                    text: currentTemplate.requirement
                })
            if (currentTemplate.warning)
                this.editor.notes.push({
                    type: 'warning',
                    text: currentTemplate.warning
                })
            if (currentTemplate.information)
                this.editor.notes.push({
                    type: 'information',
                    text: currentTemplate.information
                })
        },
        saveMarker: function() {
            var url = 'nodes'
            var data = new FormData()
            if (this.currentCategory.nodeId) {
                data.append('id', this.currentCategory.nodeId)
                url = 'nodes/edit/' + this.currentCategory.nodeId
            } else {
                data.append('id', -1)
            }
            data.append('mission-id', this.mission.id)
            data.append('icon', this.currentCategory.icon)
            data.append('subgroup', this.editor.currentCategory)
            data.append('name', this.currentCategory.name || '')
            data.append('quantity', this.currentCategory.defaultQuantity || 1)
            data.append('target', this.currentCategory.target || '')
            data.append('level', this.currentLayer)
            data.append('latitude', this.editor.clickedPoint.lat)
            data.append('longitude', this.editor.clickedPoint.lng)
            data.append('difficulty', this.$route.params.difficulty)
            data.append('group', this.currentCategory.group || '')
            data.append('searchable', +this.currentCategory.searchable)
            data.append('image', this.currentCategory.image || '')
            data.append('action', this.currentCategory.action || '')
            data.append('pickup-type', this.currentCategory.pickupType || '')
            data.append(
                'stairwell-direction',
                this.currentCategory.stairwellDirection || ''
            )
            data.append('note-type[]', 'warning')
            data.append('note-text[]', '')
            this.editor.notes.forEach((element, index) => {
                data.append('note-type[]', element.type)
                data.append('note-text[]', element.text)
            })
            this.$request(true, url, data).then(resp => {
                if (this.editor.mode === 'items' && this.currentCategory.nodeId) {
                    this.editor.currentMarkerNode.deleted = true;
                }

                if (resp.data.data.approved) {
                    resp.data.data.latLng = L.latLng(
                        resp.data.data.latitude,
                        resp.data.data.longitude
                    );
                    let node = resp.data.data;

                    let marker = this.buildMarker(node).bindPopup(this.buildPopup);
                    if (node.tooltip !== '') {
                        marker.bindTooltip(node.tooltip);
                    }
                    marker.addTo(this.overlays[node.level][node.type + '|' + node.group]);
                }
                $('#editModal').modal('hide');
                this.editor.currentMarker = null;
                let toastMessage = this.currentCategory.nodeId ? 'Item edited!' : 'Item added!';
                this.$toast.success({
                    message: toastMessage
                });
                this.updateNodeLayerState();
            })
        },
        deleteMarker: function() {
            const node = this.editor.currentMarker;
            this.$request(false, 'nodes/delete/' + node.id).then(resp => {
                node.deleted = true;
                this.editor.currentMarker = null;
                this.$toast.success({
                    message: 'Item deleted!'
                });
                this.updateNodeLayerState();
            })
        },
        deletePoly: function(item, type) {
            switch (type) {
                case 'foliage':
                    if (this.editor.mode !== 'foliage') {
                        break
                    }

                    if (
                        confirm('Are you sure you want to delete this foliage?')
                    ) {
                        this.$request(false, 'foliage/delete/' + item.id).then(
                            resp => {
                                this.$toast.success({
                                    message: 'Foliage deleted!'
                                })

                                var foliageObject = this.foliage.find(
                                    foliage => foliage.id === item.id
                                )
                                this.foliage.splice(
                                    this.foliage.indexOf(foliageObject),
                                    1
                                )
                                this.map.removeLayer(this.editor.currentMarker);
                                this.editor.currentMarker = null;
                            }
                        )
                    }

                    break
                case 'ledges':
                    if (this.editor.mode !== 'ledges') {
                        break
                    }

                    if (
                        confirm('Are you sure you want to delete this ledge?')
                    ) {
                        this.$request(false, 'ledges/delete/' + item.id).then(
                            resp => {
                                this.$toast.success({
                                    message: 'Ledge deleted!'
                                })

                                var ledgeObject = this.ledges.find(
                                    ledge => ledge.id === item.id
                                )
                                this.ledges.splice(
                                    this.ledges.indexOf(ledgeObject),
                                    1
                                )
                                this.map.removeLayer(this.editor.currentMarker);
                                this.editor.currentMarker = null;
                            }
                        )
                    }
                    break
                case 'disguise-areas':
                    if (this.editor.mode !== 'disguises') {
                        break
                    }

                    if (
                        confirm(
                            'Are you sure you want to delete this disguise area?'
                        )
                    ) {
                        this.$request(
                            false,
                            'disguise-areas/delete/' + item.id
                        ).then(resp => {
                            this.$toast.success({
                                message: 'Disguise area deleted!'
                            })

                            var disguise = this.disguises.find(
                                disguise => disguise.id === item.disguiseId
                            )
                            var disguiseArea = disguise.areas.find(
                                area => area.id === item.id
                            )
                            disguise.areas.splice(
                                disguise.areas.indexOf(disguiseArea),
                                1
                            )
                            this.map.removeLayer(this.editor.currentMarker);
                            this.editor.currentMarker = null;
                        })
                    }
                    break
            }
        },
        isLayerHidden: function(name) {
            name = name.replace('*', '')
            return (
                this.hiddenLayers.indexOf(name) != -1 ||
                this.hiddenLayers.indexOf(name.split('|')[0] + '|') != -1
            )
        },
        hideAll: function() {
            this.hiddenLayers.push('Points of Interest|', 'Weapons and Tools|', 'Navigation|');
            this.updateNodeLayerState();
        },
        toggleLayerGroup: function(layer, shouldShow) {
            if (shouldShow) {
                this.map.addLayer(layer);
            } else {
                this.map.removeLayer(layer);
            }
        },
        toggleLayer: function(name, hideAll) {
            if (name === '|') {
                this.hiddenLayers = []

                if (hideAll) {
                    this.hiddenLayers = ['Points of Interest|', 'Weapons and Tools|', 'Navigation|']
                }
            } else {
                if (name.includes('*')) {
                    // Case 1: Toggling top level category
                    let prefix = name.replace('*', '')

                    if (this.isLayerHidden(prefix)) {
                        this.$delete(this.hiddenLayers, this.hiddenLayers.indexOf(prefix))
                    } else {
                        this.hiddenLayers = this.hiddenLayers.filter(layer => !layer.includes(prefix))
                        this.hiddenLayers.push(prefix)
                    }
                }  else {
                    // Case 2: Toggling group
                    let topLevelCategory = name.split('|')[0]
                    let group = name.split('|')[1]
                    if (this.hiddenLayers.indexOf(topLevelCategory + '|') !== -1) {
                        // Case a: Top level category is hidden
                        this.$delete(this.hiddenLayers, this.hiddenLayers.indexOf(topLevelCategory + '|'))
                        this.categories[topLevelCategory]
                            .filter(category => category.type === topLevelCategory && category.group !== group)
                            .map(category => category.group)
                            .forEach(group => this.hiddenLayers.push(topLevelCategory + '|' + group))
                    } else if (this.hiddenLayers.indexOf(name) !== -1) {
                        // Case b: Group is hidden
                        this.$delete(this.hiddenLayers, this.hiddenLayers.indexOf(name))
                    } else {
                        // Case c: Groups is not hidden, nor is top level category
                        this.hiddenLayers.push(name)
                    }
                }
            }
            this.updateNodeLayerState();
        },
        toggleDraw: function(type) {
            if (type === 'ALL') {
                this.map.pm.disableDraw('Line');
                this.map.pm.disableDraw('Polygon');
                this.editor.polyActive = false;
                return;
            }

            if (this.map.pm.Draw[type]._enabled) {
                this.map.pm.disableDraw(type);
                this.editor.polyActive = false;
            } else {
                this.map.pm.enableDraw(type, {
                    snappable: false
                });
                this.editor.polyActive = true;
            }

            let toastMessage = this.editor.polyActive ? 'Drawing tools enabled' : 'Drawing tools disabled';
            this.$toast.info({
                message: toastMessage
            })
        },
        initDraw: function(e) {
            e.workingLayer.on('pm:vertexadded', e => {
                this.editor.vertices.push([e.latlng.lat, e.latlng.lng])
            })
        },
        pmLayer: function(e) {
            this.editor.workingLayers.push(e.layer)
        },
        endDraw: function(e) {
            if (this.editor.vertices.length === 0) {
                return;
            }

            var data = new FormData()
            this.editor.vertices.forEach((element, index) => {
                data.append('vertices[' + index + '][]', element[0])
                data.append('vertices[' + index + '][]', element[1])
            })
            data.append('missionId', this.mission.id)
            data.append('level', this.currentLayer)
            if (e.shape === 'Line') {
                this.$request(true, 'ledges', data).then(resp => {
                    this.editor.vertices = []
                    let ledge = resp.data.data;
                    this.ledges.push(ledge)
                    this.buildLedge(ledge).addTo(this.overlays[ledge.level]['Navigation|Ledge']);
                    this.editor.workingLayers.forEach(el => {
                        this.map.removeLayer(el)
                    })
                    this.editor.workingLayers = []
                })
            } else if (this.editor.mode === 'foliage') {
                this.$request(true, 'foliage', data).then(resp => {
                    this.editor.vertices = []
                    let foliage = resp.data.data;
                    this.foliage.push(foliage)
                    this.buildFoliage(foliage).addTo(this.overlays[foliage.level]['Navigation|Foliage']);
                    this.editor.workingLayers.forEach(el => {
                        this.map.removeLayer(el)
                    })
                    this.editor.workingLayers = []
                })
            } else if (this.editor.mode === 'disguises') {
                const disguiseId = this.editor.currentDisguise;
                data.append('disguiseId', disguiseId)
                data.append('type', this.editor.disguiseType)
                this.$request(true, 'disguise-areas', data).then(resp => {
                    this.editor.vertices = []
                    let disguiseArea = resp.data.data;
                    this.disguises
                        .find(el => el.id == disguiseId)
                        .areas.push(disguiseArea)
                    this.buildDisguiseArea(disguiseArea).addTo(this.overlays[disguiseArea.level]['Disguises|' + disguiseArea.disguiseId]);
                    this.editor.workingLayers.forEach(el => {
                        this.map.removeLayer(el)
                    })
                    this.editor.workingLayers = []
                })
            }
        },
        parseCoords: function(coords) {
            var latlngs = []
            coords.forEach(element => {
                var latlng = element.split(',')
                latlngs.push([latlng[0], latlng[1]])
            })
            return latlngs
        },
        changeDisguise: function(disguise) {
            this.editor.currentDisguise = disguise.id || disguise
            $(this.$refs.disguisePicker).selectpicker(
                'val',
                disguise.id || disguise
            )
            $('#header-disguises')
                .find('.name')
                .click()
            this.updateNodeLayerState();
        },
        partialChangeDisguise: function(disguise) {
            // Uses the ID and doesn't propagate the change back.
            this.editor.currentDisguise = disguise;
            this.updateNodeLayerState();
        },
        calculateNumber(floor) {
            var count = 0;
            for (var level in this.overlays) {
                if (parseInt(level) !== floor) {
                    continue;
                }

                let layerGroups = this.overlays[level];
                for (let layerGroup in layerGroups) {
                    if (!this.isLayerHidden(layerGroup) &&
                        !layerGroup.startsWith('Disguises|') &&
                        !layerGroup.startsWith('Navigation|Ledge') &&
                        !layerGroup.startsWith('Navigation|Foliage')) {
                        count += layerGroups[layerGroup].getLayers().length;
                    }
                }
            }
            return count + this.floorCountOverride[floor];
        },
        searchItem(event) {
            if (event.target.value === '') {
                return;
            }

            $('.search-box[data-search="items"]')
                .find('.bootstrap-select')
                .addClass('item-selected')
                .end();
            const item = event.target.value.split(';');
            this.searchedItem = {
                layer: item[0],
                name: item[1]
            };
            this.updateNodeLayerState();
        },
        updateNodeLayerState() {
            let itemName = null;
            let layer = null;
            let disguiseId = null;

            if (this.searchedItem !== null) {
                itemName = this.searchedItem.name;
                layer = this.searchedItem.layer;
            }
            if (this.editor.currentDisguise !== 'NONE') {
                disguiseId = parseInt(this.editor.currentDisguise);
            }

            this.floorsWithSearchResults = [];
            this.floorCountOverride = [];
            for (const floorString in this.overlays) {
                const floor = parseInt(floorString);
                this.floorCountOverride[floor] = 0;
                const floorLayers = this.overlays[floor];
                for (const key in floorLayers) {
                    const currentFloor = floor === parseInt(this.currentLayer);
                    const forceOff = (layer !== key || itemName === null);

                    // Find the button that toggles this layer and see if it's active or not.
                    if (key !== 'Navigation|Ledge' &&
                        key !== 'Navigation|Foliage' &&
                        !key.startsWith('Disguises|')) {
                        for (const node in floorLayers[key].getLayers()) {
                            const nodeProperties = floorLayers[key].getLayers()[node];

                            if (this.editor.mode === '') {
                                nodeProperties.dragging.disable();
                            } else {
                                nodeProperties.dragging.enable();
                            }


                            if (currentFloor && !this.isLayerHidden(key)) {
                                $(nodeProperties._icon).css('display', 'block');
                            } else {
                                $(nodeProperties._icon).css('display', 'none');
                            }

                            if (nodeProperties.options.custom.node.name === itemName && !forceOff) {
                                if (this.isLayerHidden(key)) {
                                    if (currentFloor) {
                                        $(nodeProperties._icon).css('display', 'block');
                                    }
                                    this.floorCountOverride[floor]++;
                                }
                                $(nodeProperties._icon).addClass('search-result');
                                if (this.floorsWithSearchResults.indexOf(floor) === -1) {
                                    this.floorsWithSearchResults.push(floor);
                                }
                            } else {
                                $(nodeProperties._icon).removeClass('search-result');
                            }

                            if (nodeProperties.options.custom.node.deleted === true) {
                                this.map.removeLayer(nodeProperties);
                            }
                        }
                    }

                    if (key.startsWith('Disguises|')) {
                        this.disguises.forEach(disguise => {
                            this.toggleLayerGroup(floorLayers['Disguises|' + disguise.id],
                                disguise.id === disguiseId && floor === parseInt(this.currentLayer));
                        });
                    }

                    if (key === 'Navigation|Ledge' || key === 'Navigation|Foliage') {
                        this.toggleLayerGroup(floorLayers[key], (currentFloor && !this.isLayerHidden(key)));
                    }
                }
            }
        },
        hasSearchResults(floor) {
            return this.floorsWithSearchResults.indexOf(floor) !== -1;
        },
        isSearchResult(item) {
            if (this.searchedItem == null) return false
            return (
                this.searchedItem.items.filter(el => el.id == item.id).length >
                0
            )
        },
        clearSearch() {
            this.searchedItem = null;
            $('.search-box[data-search="items"]')
                .find('.bootstrap-select')
                .removeClass('item-selected')
                .end();
            $(this.$refs.itemSearch).selectpicker('val', '');
            this.updateNodeLayerState();
        },
        showCopyDisguiseModal() {
            $('#copy-disguises-modal').modal('show')
        },
        copyDisguiseAreas() {
            let formData = new FormData()
            formData.append('original-disguise', this.copyDisguiseArea.source)
            formData.append(
                'target-disguise',
                this.copyDisguiseArea.destination
            )
            this.$request(true, 'disguise-areas/copy', formData).then(resp => {
                $('#copy-disguise-modal').modal('hide')
                this.$toast.success({
                    title: 'Changes Saved',
                    message:
                        'Disguise areas copied. Refresh the page to see the changes'
                })
            })
        }
    },
    beforeCreate: function() {
        if (this.$store.state.mission == null) {
            this.$request(
                false,
                'v1/games/' +
                    this.$route.params.slug +
                    '/locations/' +
                    this.$route.params.location +
                    '/missions/' +
                    this.$route.params.mission
            ).then(resp => {
                this.$store.commit('SET_MISSION', resp.data[0])
            })
        }
    },
    created: function() {
        const $body = $('body');
        $body.on('click', '[data-action="edit-btn"]', this.editMarker);
        $body.on('click', '[data-action="delete-btn"]', this.deleteMarker);

        if (this.game === null || this.game.slug !== this.$route.params.slug)
            this.$store.dispatch('loadGame', this.$route.params.slug)
        this.$request(
            false,
            'v1/games/' +
                this.$route.params.slug +
                '/locations/' +
                this.$route.params.location +
                '/missions/' +
                this.$route.params.mission +
                '/' +
                this.$route.params.difficulty +
                '/map'
        ).then(resp => {
            if (this.$store.state.mission == null) {
                this.$store.commit('SET_MISSION', resp.data.mission);
            }

            this.$route.meta.title = resp.data.mission.name
            this.currentLayer = resp.data.mission.startingFloorNumber
            for (var group in resp.data.nodes) {
                for (var subgroup in resp.data.nodes[group].items) {
                    resp.data.nodes[group].items[subgroup].items.forEach(
                        item => {
                            item.latLng = L.latLng(
                                item.latitude,
                                item.longitude
                            )
                        }
                    )
                }
            }
            this.disguises = resp.data.disguises
            this.ledges = resp.data.ledges
            this.foliage = resp.data.foliage
            this.nodes = resp.data.nodes
            this.searchableNodes = resp.data.searchableNodes
            resp.data.categories.forEach(category => {
                if (!this.categories[category.type]) {
                    this.categories[category.type] = [];
                }
                this.categories[category.type].push(category);
            });

            // Initialize g_overlays for each floor
            for (let i = this.mission.lowestFloorNumber; i <= this.mission.highestFloorNumber; i++) {
                this.overlays[i] = {};
            }

            resp.data.categories.forEach(category => {
                for (let i = this.mission.lowestFloorNumber; i <= this.mission.highestFloorNumber; i++) {
                    this.overlays[i][category.type + '|' + category.group] = L.layerGroup();
                    this.layerGroups.push(this.overlays[i][category.type + '|' + category.group]);
                }
            });

            // Layers for disguises
            console.log(resp.data.disguises);
            resp.data.disguises.forEach(disguise => {
                for (let i = this.mission.lowestFloorNumber; i <= this.mission.highestFloorNumber; i++) {
                    this.overlays[i]['Disguises|' + disguise.id] = L.layerGroup();
                    this.layerGroups.push(this.overlays[i]['Disguises|' + disguise.id]);
                }
            });

            for (let typeName in resp.data.nodes) {
                let nodeType = resp.data.nodes[typeName];

                for (let groupName in nodeType.items) {
                    let group = nodeType.items[groupName];
                    group.items.forEach(node => {
                        let marker = this.buildMarker(node).bindPopup(this.buildPopup);
                        if (node.tooltip !== '') {
                            marker.bindTooltip(node.tooltip);
                        }

                        marker.addTo(this.overlays[node.level][nodeType.name + '|' + group.name]);
                    })
                }
            }

            resp.data.ledges.forEach(ledge => {
                this.buildLedge(ledge).addTo(this.overlays[ledge.level]['Navigation|Ledge']);
            });

            resp.data.foliage.forEach(foliage => {
                this.buildFoliage(foliage).addTo(this.overlays[foliage.level]['Navigation|Foliage']);
            });

            resp.data.disguises.forEach(disguise => {
                disguise.areas.forEach(area => {
                    this.buildDisguiseArea(area).addTo(this.overlays[area.level]['Disguises|' + disguise.id]);
                });
            });

            this.$nextTick(() => {
                // Build tile layers for each floor
                for (let i = this.mission.lowestFloorNumber; i <= this.mission.highestFloorNumber; i++) {
                    let mapTileLayer = L.tileLayer(this.mapUrl + i + '/{z}/{x}/{y}.png', {
                        noWrap: true,
                        minZoom: 3,
                        maxZoom: 5
                    });
                    this.layerGroups.push(mapTileLayer);
                    this.mapLayers[i] = mapTileLayer;
                }

                if (this.mission.satelliteView) {
                    let mapTileLayer = L.tileLayer(this.mapUrl + '-99/{z}/{x}/{y}.png', {
                        noWrap: true,
                        minZoom: 3,
                        maxZoom: 5
                    });
                    this.layerGroups.push(mapTileLayer);
                    this.mapLayers[-99] = mapTileLayer;
                }

                this.map = L.map('map', {
                    maxZoom: 5,
                    minZoom: 3,
                    crs: L.CRS.Simple,
                    layers: this.layerGroups,
                    renderer: L.canvas()
                }).setView([this.mission.mapCenterLatitude, this.mission.mapCenterLongitude], 3);
                let topLeftCoordinate = this.mission.topLeftCoordinate.split(',');
                let bottomRightCoordinate = this.mission.bottomRightCoordinate.split(',');
                this.map.setMaxBounds([topLeftCoordinate, bottomRightCoordinate]);

                const zoom = L.control.zoom({position: 'topright'});
                zoom.addTo(this.map);
                $('#map-control').append(zoom.getContainer());

                for (let i = this.mission.lowestFloorNumber; i <= this.mission.highestFloorNumber; i++) {
                    this.map.removeLayer(this.mapLayers[i]);
                }
                if (this.mission.satelliteView) {
                    this.map.removeLayer(this.mapLayers[-99]);
                }

                this.map.addLayer(this.mapLayers[this.mission.startingFloorNumber]);

                // "Show" all items; CSS will handle showing / hiding
                for (let i = this.mission.lowestFloorNumber; i <= this.mission.highestFloorNumber; i++) {
                    for (let j in this.overlays[i]) {
                        this.toggleLayerGroup(this.overlays[i][j], true);
                    }
                }
                this.updateNodeLayerState();

                this.mapLoaded = true;
                //Is not needed directly at start
                this.$request(false, 'v1/editor/templates').then(resp => {
                    this.editor.templates = resp.data;
                    this.$nextTick(() => {
                        $('.selectpicker').selectpicker()
                    })
                });
                this.$request(false, 'v1/editor/icons').then(resp => {
                    this.editor.icons = resp.data;
                    this.$nextTick(() => {
                        $(this.$refs.iconPicker).selectpicker('destroy');
                        $(this.$refs.iconPicker).selectpicker()
                    })
                });

                this.map.on('click', this.addMarker);

                this.map.on('pm:drawstart', this.initDraw);

                this.map.on('pm:create', this.pmLayer);

                this.map.on('pm:drawend', this.endDraw);

                this.map.on('zoomend', () => {
                    let zoomLevel = this.map.getZoom();
                    console.log(zoomLevel);

                    var fonts = {
                        3: '.8em',
                        4: '1em',
                        5: '1.2em'
                    };

                    $('.area-icon').css('font-size', fonts[zoomLevel]);
                });
            })
        })
    }
}
</script>

<style lang="scss">
html {
    height: 100%;
    overflow-y: scroll;
}

.marker-icon,
.marker-icon:focus {
    background: rgba(0, 0, 0, 0.25);
}

.header {
    max-width: 368px;
    margin-top: 20px;
    margin-bottom: 20px;

    a {
        opacity: 0.85;

        &:hover {
            opacity: 1;
        }
    }
}

#map {
    z-index: 0;
    position: fixed;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;

    .leaflet-control-container {
        display: none;
    }

    .leaflet-popup-close-button {
        font-size: 2rem;
        width: 25px;
    }

    .leaflet-popup-content-wrapper {
        padding: 0;
        border-radius: 5px;

        .leaflet-popup-content {
            width: 300px !important;
            margin: 0 !important;
            padding-bottom: 10px !important;

            img {
                width: 300px !important;
                height: 200px !important;
                border-top-left-radius: 5px;
                border-top-right-radius: 5px;
                background: url('/img/map-icons/loader.gif') no-repeat center
                    #fff;
            }

            div[data-name='name'] {
                color: #000;
                text-transform: uppercase;
                padding: 10px 10px 0;
                font-size: 1.3em;
                font-weight: bolder;
            }

            div[data-name='group'] {
                color: #aaa;
                text-transform: uppercase;
                font-weight: bolder;
                padding: 0 10px;
            }

            [data-name='target'] {
                padding: 0 10px;
                color: #666;
            }

            [data-name='notes'] {
                margin-top: 10px;
                margin-bottom: 10px;

                > div {
                    padding: 10px;

                    &:not(:last-child) {
                        margin-bottom: 10px;
                    }
                }

                .requirement {
                    .in-game-description {
                        display: none;
                    }

                    color: #721c24;
                    background: #f8d7da;
                    border-left: solid 3px #d00000;
                }

                .warning {
                    .in-game-description {
                        display: none;
                    }

                    color: #856404;
                    background: #fff3cd;
                    border-left: solid 3px #ffa500;
                }

                .info {
                    .in-game-description {
                        display: none;
                    }

                    color: #0c5460;
                    background: #b7d4ff;
                    border-left: solid 3px #0000e0;
                }

                .description {
                    .in-game-description {
                        text-transform: uppercase;
                        font-weight: bolder;
                        color: #404040;
                    }

                    color: #666;
                    background: #e4e4e4;
                    border-left: solid 3px #666666;
                }
            }

            [data-action='delete-btn'] {
                margin-left: 10px;
            }
        }
    }
}

.leaflet-container {
    background: #464646;
}

@media (min-width: 768px) {
    .navbar-fixed-right {
        display: block;
        float: right;
        width: 400px;
        min-height: 100vh;
        background: #000;
    }

    .navbar-toggler {
        display: none;
    }

    .navbar-collapse {
        display: block !important;
    }

    .accordion > .floor-toggle {
        display: none;
    }

    .content > .floor-toggle {
        display: block;
        position: fixed;
        top: 110px;
        left: 50px;
    }
}

@media (max-width: 767px) {
    .navbar-fixed-right {
        background: #000;
    }

    .navbar-toggler {
        display: block;
    }

    .accordion > .floor-toggle {
        display: block;
        margin-bottom: 10px;
    }

    .content > .floor-toggle {
        display: none;
    }
}

.navbar-toggler {
    background: #2a2d31;
}

.leaflet-control-zoom {
    float: none;
    display: inline-block;
    box-sizing: content-box;
}

.leaflet-bar {
    box-shadow: none;
    border: none !important;
}

.leaflet-control-zoom-in {
    border: 2px solid #fff !important;
    margin-bottom: 10px;
    box-sizing: content-box;
}

.leaflet-control-zoom-out {
    border: 2px solid #fff !important;
    box-sizing: content-box;
    margin-left: 5px;
}

.leaflet-control-zoom-in,
.leaflet-control-zoom-out {
    float: left !important;
    border-radius: 3px !important;
    padding: 5px !important;
    background: rgba(22, 24, 29, 0.75) !important;
    color: #fff !important;
    box-shadow: none !important;

    &:not(.leaflet-disabled):hover {
        background: white !important;
        color: #000 !important;
    }

    &.leaflet-disabled {
        color: rgba(141, 163, 180, 0.75) !important;
        border: solid 2px rgba(141, 163, 180, 0.75) !important;
    }
}

.leaflet-marker-icon {
    &.search-result {
        z-index: 9999 !important;
        background: rgba(255, 0, 60, 0.75);
        padding: 15px !important;
        margin: -33px 0 0 -33px !important;
        border-radius: 50%;
        border: 2px solid #ff003c;
        opacity: 0.85 !important;
        box-sizing: content-box;
    }

    &.area-icon {
        color: #fff;
        text-shadow: #000 1px 1px 1px;
        font-size: 0.8em;
        width: inherit !important;
        height: inherit !important;
        white-space: nowrap;
        text-align: center;
    }
}

.accordion {
    .hide-or-select-all {
        width: 368px;
    }

    .card {
        margin-top: 5px;
        margin-bottom: 5px;
        border-radius: 3px !important;
        background: #000;
        border: none;
        width: 368px;

        > .card-header {
            background: #000;
            border-radius: 0;
            border: none;
            padding: 0;
            margin-bottom: 10px;

            .visibility-toggle {
                width: 40px;
                text-align: center;

                .fa-eye-slash {
                    display: none;
                }

                .fa-eye {
                    display: inline;
                }

                &.map-hidden {
                    .fa-eye-slash {
                        display: inline;
                    }

                    .fa-eye {
                        display: none;
                    }
                }
            }

            .name {
                margin-right: 14px;
                width: 313px;
            }

            .name,
            .visibility-toggle {
                &.control-button {
                    width: 368px;
                    padding-left: 12px;
                    padding-top: 6px;
                    padding-bottom: 6px;
                    transition: color 0.15s ease-in-out,
                        background-color 0.15s ease-in-out,
                        border-color 0.15s ease-in-out,
                        box-shadow 0.15s ease-in-out;

                    &:hover {
                        background: rgba(22, 24, 29, 0.75);
                    }
                }

                display: inline-block;
                padding: 10px;
                background: rgba(22, 24, 29, 0.75);
                color: #fff;
                cursor: pointer;
                height: 40px;
                border-radius: 3px;

                .fa-caret-down {
                    display: none;
                }

                .fa-caret-up {
                    display: inline;
                }

                &.collapsed {
                    .fa-caret-up {
                        display: none;
                    }

                    .fa-caret-down {
                        display: inline;
                    }
                }

                &:hover {
                    color: #fff;
                    text-decoration: none;
                    background: #33363a;
                }
            }

            &#header-disguises {
                margin-bottom: 0;
            }
        }

        .card-body {
            background: #000;
            border-radius: 0;
            border: none;
            padding: 0;
            margin-bottom: 10px;

            .visibility-toggle {
                width: 40px;
                text-align: center;
            }

            .full-width {
                .name {
                    width: 313px;
                }

                margin-bottom: 5px;
            }

            .half-width {
                width: 180px;
                display: inline-block;
                margin-bottom: 5px;
            }

            .name {
                margin-right: 10px;
                vertical-align: top;
                opacity: 0.85;

                span {
                    vertical-align: middle;
                }

                &.map-hidden {
                    opacity: 0.65;

                    span {
                        text-decoration: line-through;
                    }
                }
            }

            .visibility-toggle {
                background: #2a2d31;
                padding: 10px;
            }

            .name,
            .visibility-toggle {
                display: inline-block;
                color: #fff;
                cursor: pointer;
                height: 40px;
                border-radius: 3px;

                &.hidden {
                    text-decoration: line-through;
                }

                .fa-caret-down {
                    display: none;
                }

                .fa-caret-up {
                    display: inline;
                    vertical-align: text-top;
                }

                &.collapsed {
                    .fa-caret-up {
                        display: none;
                    }

                    .fa-caret-down {
                        display: inline;
                        vertical-align: text-top;
                    }
                }

                &:hover {
                    color: #fff;
                    text-decoration: none;
                    opacity: 1;
                }
            }

            .collapsible-items {
                margin-top: 5px;

                ul {
                    list-style-type: none;
                    padding: 0 15px 10px 15px;
                    background: #2a2d31;
                    border-radius: 3px;
                }

                li {
                    padding-top: 15px;
                    color: #fff;

                    &:hover {
                        opacity: 1;
                    }
                }
            }
        }
    }
}

#map-control {
    width: 368px;

    .control-buttons {
        float: right;
        margin-bottom: 10px;
    }

    .control-button {
        height: 40px;
        border-radius: 3px;
        background: rgba(22, 24, 29, 0.75);
        color: #fff;
        box-shadow: none;
        border: solid 2px #fff;
        margin-left: 5px;
        transition: none;

        &:hover {
            color: #000;
            background: #fff;
        }

        .flag {
            margin: -20px -24px -24px -24px !important;
            transform: scale(.50) !important;
            -ms-transform: scale(.50) !important;
            -webkit-transform: scale(.50) !important;
            -moz-transform: scale(.50) !important;
        }
    }
}

.edit-menu,
.items-menu,
.ledges-menu,
.foliage-menu,
.disguise-trespassing-menu {
    h2 {
        color: #fff;
        text-transform: uppercase;
    }

    h3 {
        color: #fff;
    }

    p {
        color: #fff;
    }

    .editor-button {
        border-radius: 3px;
        background: rgba(22, 24, 29, 0.75);
        color: #fff;
        box-shadow: none;
        border: solid 2px #2a2d31;
        opacity: 0.85;
        margin-bottom: 10px;
        width: 100%;
        padding: 20px;
        text-align: left;

        h3 {
            font-size: 1.25rem;
        }

        &:hover,
        &.selected {
            cursor: pointer;
            opacity: 1;
            border-color: #fff;
        }
    }
}

#hide-all,
#show-all,
.search-box .control-button {
    height: 40px;
    border-radius: 3px;
    background: rgba(22, 24, 29, 0.75);
    color: #fff;
    box-shadow: none;
    border: solid 2px #2a2d31;
    opacity: 0.85;
    width: 160px;
    margin-bottom: 10px;

    &:hover {
        opacity: 1;
        border-color: #fff;
    }
}

.search-box {
    .bootstrap-select {
        width: 368px !important;

        &.item-selected {
            width: 313px !important;
        }

        > .dropdown-toggle {
            &.bs-placeholder {
                color: #fff;

                &:hover,
                &:active,
                &:focus {
                    color: #fff;
                }
            }
        }

        .dropdown-header {
            background: #eee;
            text-transform: uppercase;
        }
    }

    #clear-search.control-button,
    #clear-disguise-search.control-button {
        display: inline-block;
        margin-left: 10px;
        width: 40px;
        height: 40px;
    }
}

.search-box .control-button {
    width: 100%;
}

#body-disguises {
    margin-top: -10px;

    .disguises {
        .full-width {
            padding-top: 10px;
            padding-right: 20px;
            text-align: right;

            &:hover {
                background: #16181d;
                cursor: pointer;
            }

            &.selected {
                background: rgb(41, 44, 52);
            }

            img.disguise-image {
                display: inline-block;
                width: 150px;
                height: 112px;
            }

            div.disguise-image {
                height: 112px;

                i {
                    margin-top: 24px;
                    color: #fff;
                }
            }

            .disguise-info {
                display: inline-block;
                font-weight: bolder;
                color: #fff;
            }
        }
    }
}

#show-all {
    float: right;
}

.tooltip-inner {
    background: white;
    color: black;
}

.editor-enabled {
    color: #fff;
    text-transform: uppercase;
    border: solid 2px #ff003c;
    text-align: center;
    margin-bottom: 5px;
    background-color: #ff003c;

    h3 {
        margin: .5rem 0;
    }
}

.floor-toggle {
    z-index: 1;
    width: 150px;
    border-radius: 3px;
    border: 2px solid #fff;
    background: rgba(22, 24, 29, 0.75);

    .floor-info {
        margin: 0;
        border-bottom: 2px solid #fff;
        color: #fff;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;

        .floor {
            display: inline-block;
            padding: 10px 15px;
            /*border-right: solid 2px #fff;*/
            width: 100px;
            text-align: center;
        }

        .item-count {
            display: inline-block;
            background: #ddd;
            color: #000;
            //margin-left: -4px;
            width: 46px;
            padding: 10px 15px;
            text-align: center;

            &.has-search-results {
                background: #ff003c;
                color: #fff;
            }
        }

        &:hover {
            background: #33363a;
        }

        &:last-child {
            border-bottom: none;
        }

        &.selected {
            background: #fff;
            color: #000;
            margin-top: -1px;
        }
    }
}

.overlay {
    position: fixed;
    text-align: center;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 9999;
    color: #fff;
    display: flex;
    align-items: center;
    justify-items: center;

    .background {
        position: fixed;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        overflow: hidden;
        z-index: -100;

        .video {
            position: absolute;
            top: 50%;
            left: 50%;
            width: auto;
            height: auto;
            min-width: 100%;
            min-height: 100%;
            transform: translate(-50%, -50%);

            @media (max-width: 767px) {
                & {
                    display: none;
                }
            }
        }

        @media (max-width: 767px) {
            & {
                background: url('/img/jpg/loading.jpg') center center / cover
                    no-repeat;
            }
        }
    }

    .overlay-container {
        margin: 0 auto;
        box-shadow: 5px 5px 40px #000;

        .footer {
            /* White background */
            background: #fff;
            color: #000;
            text-align: left;
            padding: 10px;

            .footer-image {
                display: inline-block;
                vertical-align: top;
                margin-right: 5px;

                img {
                    height: 48px;
                    width: 48px;
                }
            }

            .footer-text {
                display: inline-block;
                text-transform: uppercase;

                h1 {
                    font-size: 1.5rem;
                    margin-bottom: 0;
                }

                h2 {
                    font-size: 1rem;
                    margin-bottom: 0;
                    color: #ff003c;
                }
            }
        }
    }
}

[data-disguise-id] {
    border: solid 2px #000;
    border-radius: 3px;

    &:hover {
        border: solid 2px #fff;
    }
}

    #editModal .dropdown-toggle img {
        background: rgba(0, 0, 0, .15);
        border-radius: 3px;
    }
</style>
