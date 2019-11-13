<template>
    <div>
        <div v-if="!mapLoaded && mission != null" class="overlay" :style="'background: #ccc url(\'/img/jpg/mission-backgrounds/' + mission.background + '.jpg\') no-repeat; background-size: cover'">
            <div class="overlay-container">
                <img
                    class="img-fluid"
                    :src="'/img/' + loadingTile"
                    alt="Mission Thumbnail"
                />
                <div class="footer">
                    <div class="footer-image">
                        <img
                            src="/img/game-icons/mission-inverted.png"
                            class="img-fluid"
                            alt="Mission Icon"
                        />
                    </div>
                    <div class="footer-text">
                        <h2>{{ mission.missionType }}</h2>
                        <h1>{{ mission.name }}</h1>
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
                        Level {{ i }}
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
                        Satellite
                    </div>
                </div>
            </div>
            <l-map
                id="map"
                ref="map"
                @click="addMarker"
                :minZoom="3"
                :maxZoom="5"
                :maxBounds="[
                    [
                        this.mission.topLeftCoordinate.split(',')[0],
                        this.mission.topLeftCoordinate.split(',')[1]
                    ],
                    [
                        this.mission.bottomRightCoordinate.split(',')[0],
                        this.mission.bottomRightCoordinate.split(',')[1]
                    ]
                ]"
                :crs="crs"
                @pm:drawstart="initDraw"
                @pm:create="pmLayer"
                @pm:drawend="endDraw"
            >
                <l-tile-layer
                    v-for="floor in range(
                        mission.lowestFloorNumber,
                        mission.highestFloorNumber
                    )"
                    :key="floor"
                    :noWrap="true"
                    :visible="currentLayer === floor"
                    :url="mapUrl + floor + '/{z}/{x}/{y}.png'"
                ></l-tile-layer>
                <l-tile-layer
                    v-if="mission.satelliteView"
                    :noWrap="true"
                    :visible="currentLayer === -99"
                    :url="mapUrl + '-99/{z}/{x}/{y}.png'"
                ></l-tile-layer>
                <div
                    v-for="floor in range(
                        mission.lowestFloorNumber,
                        mission.highestFloorNumber
                    )"
                    :key="'layer' + floor"
                >
                    <l-layer-group
                        v-for="disguise in disguises"
                        :key="floor + disguise.name"
                        :visible="
                            currentLayer == floor &&
                                editor.currentDisguise == disguise.id
                        "
                    >
                        <l-polygon
                            v-for="item in disguise.areas.filter(
                                el => el.level == floor
                            )"
                            :key="item.id"
                            :lat-lngs="parseCoords(item.vertices)"
                            :fillColor="
                                item.type === 'trespassing' ? 'yellow' : '#f00'
                            "
                            :stroke="false"
                            :weight="4"
                            :opacity="0.75"
                            @click="deletePoly(item, 'disguise-areas')"
                        >
                            <l-tooltip>
                                {{
                                    item.type === 'trespassing'
                                        ? 'Trespassing'
                                        : 'Hostile'
                                }}
                            </l-tooltip>
                        </l-polygon>
                    </l-layer-group>
                    <l-layer-group
                        v-for="(group, key) in layerGroups"
                        :key="floor + key"
                        :visible="currentLayer == floor && !isLayerHidden(key)"
                    >
                        <div v-if="group.name === 'Ledge'">
                            <l-polyline
                                v-for="ledge in ledges.filter(
                                    el => el.level == floor
                                )"
                                :key="ledge.id"
                                color="#fff"
                                :weight="4"
                                :opacity="0.75"
                                :lat-lngs="parseCoords(ledge.vertices)"
                                @click="deletePoly(ledge, 'ledges')"
                            >
                                <l-tooltip>Ledge</l-tooltip>
                            </l-polyline>
                        </div>
                        <div v-else-if="group.name === 'Foliage'">
                            <l-polygon
                                v-for="item in foliage.filter(
                                    el => el.level == floor
                                )"
                                :key="item.id"
                                color="#248f24"
                                fillColor="#248f24"
                                :weight="4"
                                :opacity="0.75"
                                :lat-lngs="parseCoords(item.vertices)"
                                @click="deletePoly(item, 'foliage')"
                            >
                                <l-tooltip>Foliage</l-tooltip>
                            </l-polygon>
                        </div>
                        <div v-else>
                            <l-marker
                                v-for="item in group.items.filter(
                                    el => el.level == floor
                                )"
                                :key="item.id"
                                :latLng="item.latLng"
                                :draggable="editor.mode === 'items'"
                                @dragend="moveMarker($event, item)"
                            >
                                <l-icon
                                    :icon-url="
                                        '/img/map-icons/' + item.icon + '.png'
                                    "
                                    :icon-size="[32, 32]"
                                    :icon-anchor="[16, 16]"
                                    :class-name="
                                        isSearchResult(item)
                                            ? 'search-result'
                                            : ''
                                    "
                                ></l-icon>
                                <l-popup>
                                    <img
                                        v-if="item.image"
                                        :src="'/img/png/' + item.image + '.png'"
                                        alt="Image template holder"
                                    />
                                    <div data-name="name">{{ item.name }}</div>
                                    <div data-name="group">
                                        {{ item.group }}
                                    </div>
                                    <div data-name="target">
                                        <i
                                            v-if="item.target"
                                            :class="'far ' + item.targetIcon"
                                        ></i>
                                        <span>{{ item.target }}</span>
                                    </div>
                                    <div data-name="notes">
                                        <div
                                            v-for="note in item.notes"
                                            :key="note.id"
                                            :class="note.type"
                                        >
                                            <div class="in-game-description">
                                                In-game Description:
                                            </div>
                                            <div data-name="note-contents">
                                                {{ note.text }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="btn-group">
                                        <button
                                            class="btn btn-danger btn-sm"
                                            data-action="delete-btn"
                                            @click="deleteMarker(item)"
                                            v-tooltip:top="'Delete'"
                                        >
                                            <i class="fas fa-times"></i>
                                        </button>
                                        <button
                                            class="btn btn-warning btn-sm"
                                            data-action="edit-btn"
                                            @click="editMarker(item)"
                                            data-node-id="x"
                                            v-tooltip:top="'Edit'"
                                        >
                                            <i class="fas fa-pencil-alt"></i>
                                        </button>
                                    </div>
                                </l-popup>
                                <l-tooltip v-if="item.tooltip">
                                    {{ item.tooltip }}
                                </l-tooltip>
                            </l-marker>
                        </div>
                    </l-layer-group>
                </div>
            </l-map>
            <nav class="navbar navbar-fixed-right navbar-dark">
                <button
                    class="navbar-toggler"
                    type="button"
                    data-toggle="collapse"
                    data-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent"
                    aria-expanded="false"
                    aria-label="Toggle navigation"
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
                                src="/img/png/logos/map-header.png"
                                class="img-fluid"
                            />
                        </router-link>
                    </div>
                    <div class="editor-enabled" v-if="editor.enabled">
                        <h3 v-if="editor.mode === ''">Editor Enabled</h3>
                        <h3 v-else-if="editor.mode === 'items'">
                            ADD / REMOVE ITEMS
                        </h3>
                        <h3 v-else-if="editor.mode === 'ledges'">
                            ADD / REMOVE LEDGES
                        </h3>
                        <h3 v-else-if="editor.mode === 'foliage'">
                            ADD / REMOVE FOLIAGE
                        </h3>
                        <h3 v-else-if="editor.mode === 'disguises'">
                            MANAGE DISGUISE AREAS
                        </h3>
                    </div>
                    <div id="map-control">
                        <div class="control-buttons">
                            <button
                                v-if="isLoggedIn"
                                id="edit-button"
                                @click="editor.enabled = !editor.enabled"
                                class="btn control-button"
                                v-tooltip:top="'Edit Map'"
                            >
                                <i class="fas fa-pencil-alt"></i>
                            </button>
                            <router-link :to="{ name: 'profile' }">
                                <button
                                    v-if="isLoggedIn"
                                    class="btn control-button"
                                    v-tooltip:top="'Profile'"
                                >
                                    <i class="fas fa-user-circle"></i>
                                </button>
                            </router-link>
                            <a href="#">
                                <button
                                    v-if="isLoggedIn"
                                    class="btn control-button"
                                    v-tooltip:top="'Log Out'"
                                >
                                    <i class="fas fa-sign-out-alt"></i>
                                </button>
                            </a>
                            <router-link :to="{ name: 'login' }">
                                <button
                                    v-if="!isLoggedIn"
                                    class="btn control-button"
                                    v-tooltip:bottom="
                                        'Login / Register to edit'
                                    "
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
                                    Level {{ i }}
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
                                    Satellite
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
                                v-tooltip:top="'Clear Search'"
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
                                                    ? 'Disguises'
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
                                            <p class="disguise-info">None</p>
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
                                v-tooltip:top="'Clear Search'"
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
                                Hide All
                            </button>
                            <button
                                id="show-all"
                                @click="toggleLayer('|', false)"
                                class="btn control-button"
                            >
                                <i class="far fa-fw fa-eye"></i>
                                Show All
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
                                            <span>{{ group.name }}</span>
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
                                        {{ type.name }}
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
                                                <span>{{ group.name }}</span>
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
                        <h2>Editor Menu</h2>
                        <h3>What would you like to do?</h3>
                        <div class="editor-button" @click="editorMenu('items')">
                            <h3>
                                <i class="fas fa-fw fa-map-marker-alt"></i>
                                Add / Remove Items
                            </h3>
                        </div>
                        <div
                            v-if="mission.missionType != 'Sniper Assassin'"
                            class="editor-button"
                            @click="editorMenu('ledges')"
                        >
                            <h3>
                                <i class="fas fa-fw fa-bezier-curve"></i>
                                Add / Remove Ledges
                            </h3>
                        </div>
                        <div
                            v-if="mission.missionType != 'Sniper Assassin'"
                            class="editor-button"
                            @click="editorMenu('foliage')"
                        >
                            <h3>
                                <i class="fas fa-fw fa-leaf"></i>
                                Add / Remove Foliage
                            </h3>
                        </div>
                        <div
                            v-if="mission.missionType != 'Sniper Assassin'"
                            class="editor-button"
                            @click="editorMenu('disguises')"
                        >
                            <h3>
                                <i class="fas fa-fw fa-user-tie"></i>
                                Manage Disguise Areas
                            </h3>
                        </div>
                        <p>
                            Click the
                            <i class="fas fa-pencil-alt"></i>
                            icon to close the editor menu.
                        </p>
                    </div>
                    <div
                        class="items-menu"
                        v-show="editor.enabled && editor.mode === 'items'"
                    >
                        <p>
                            <i class="fas fa-fw fa-plus-circle"></i>
                            Click anywhere on the map to add a new item.
                        </p>
                        <p>
                            <i class="fas fa-fw fa-arrows-alt"></i>
                            Drag and drop an existing item to move it.
                        </p>
                        <p>
                            <i class="fas fa-fw fa-trash"></i>
                            Click on an existing item and then the "Delete"
                            button to delete it.
                        </p>
                        <div class="editor-button" @click="editorMenu('')">
                            <h3>
                                <i class="fas fa-times-circle"></i>
                                Close Item Menu
                            </h3>
                        </div>
                    </div>
                    <div
                        class="ledges-menu"
                        v-show="editor.enabled && editor.mode === 'ledges'"
                    >
                        <p data-ledge="delete-help">
                            <i class="fas fa-trash"></i>
                            Click on an existing ledge to delete it.
                        </p>
                        <div class="editor-button" @click="toggleDraw('Line')">
                            <h3>
                                <i class="fas fa-plus-circle"></i>
                                Add Ledge
                            </h3>
                            <p>Click here to enable / disable ledge builder</p>
                        </div>
                        <div
                            class="editor-button"
                            @click="
                                editorMenu('')
                                $refs.map.mapObject.pm.disableDraw('Line')
                            "
                        >
                            <h3>
                                <i class="fas fa-times-circle"></i>
                                Close Ledge Menu
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
                            @click="toggleDraw('Polygon')"
                        >
                            <h3>
                                <i class="fas fa-plus-circle"></i>
                                Add Foliage
                            </h3>
                            <p>
                                Click here to enable / disable foliage builder
                            </p>
                        </div>
                        <div
                            class="editor-button"
                            @click="
                                editorMenu('')
                                $refs.map.mapObject.pm.disableDraw('Polygon')
                            "
                        >
                            <h3>
                                <i class="fas fa-times-circle"></i>
                                Close Foliage Menu
                            </h3>
                        </div>
                    </div>
                    <div
                        class="disguise-trespassing-menu"
                        v-show="editor.enabled && editor.mode === 'disguises'"
                    >
                        <p data-disguise="delete-help">
                            <i class="fas fa-trash"></i>
                            Click on an existing region to delete it.
                        </p>
                        <p>
                            Select a disguise from the dropdown below to edit
                            its layout.
                        </p>
                        <div class="search-box">
                            <select
                                ref="disguisePicker"
                                @change="
                                    editor.currentDisguise = $event.target.value
                                "
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
                            @click="
                                toggleDraw('Polygon')
                                editor.disguiseType = 'trespassing'
                            "
                            v-if="editor.currentDisguise != 'NONE'"
                        >
                            <h3>
                                <i class="fas fa-plus-circle"></i>
                                Add Trespassing Region
                            </h3>
                            <p>
                                Click here to enable / disable trespassing
                                region builder
                            </p>
                        </div>
                        <div
                            class="editor-button"
                            data-disguise="add"
                            data-type="hostile"
                            @click="
                                toggleDraw('Polygon')
                                editor.disguiseType = 'hostile'
                            "
                            v-if="editor.currentDisguise != 'NONE'"
                        >
                            <h3>
                                <i class="fas fa-plus-circle"></i>
                                Add Hostile Region
                            </h3>
                            <p>
                                Click here to enable / disable hostile region
                                builder
                            </p>
                        </div>
                        <div
                            class="editor-button"
                            data-disguise="copy"
                            v-if="editor.currentDisguise === 'NONE'"
                            @click="showCopyDisguiseModal"
                        >
                            <h3>
                                <i class="fas fa-copy"></i>
                                Copy Regions
                            </h3>
                            <p>
                                Click here to copy disguise regions from one
                                disguise to another
                            </p>
                        </div>
                        <div
                            class="editor-button"
                            @click="
                                editorMenu('')
                                $refs.map.mapObject.pm.disableDraw('Polygon')
                            "
                        >
                            <h3>
                                <i class="fas fa-times-circle"></i>
                                Close Disguise Area Menu
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
                    When copying, any existing target disguise regions will be
                    deleted before copying.
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="original-disguise">
                                Source Disguise
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
                                    Select
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
                                Target Disguise
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
                                    Select
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
                            alt="Submit Icon"
                        />
                        <img
                            src="/img/game-icons/modal-continue-inverted.png"
                            class="inverted img-fluid"
                            alt="Submit Icon"
                        />
                        Copy
                    </game-button>
                    <game-button type="button" data-dismiss="modal">
                        <img
                            src="/img/game-icons/modal-close.png"
                            class="normal img-fluid"
                            alt="Cancel Icon"
                        />
                        <img
                            src="/img/game-icons/modal-close-inverted.png"
                            class="inverted img-fluid"
                            alt="Cancel Icon"
                        />
                        Cancel
                    </game-button>
                </template>
            </modal>
            <div
                class="modal"
                ref="confirmMoveModal"
                id="confirm-move-modal"
                tabindex="-1"
                role="dialog"
            >
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Confirm Move</h5>
                            <button
                                type="button"
                                class="close"
                                data-dismiss="modal"
                                aria-label="Close"
                            >
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-warning">
                                Are you sure you want to re-position this item?
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button
                                type="button"
                                class="btn btn-secondary"
                                data-dismiss="modal"
                            >
                                No, cancel
                            </button>
                            <button
                                type="button"
                                @click="confirmMove"
                                class="btn btn-primary"
                            >
                                Yes, save
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div
                class="modal"
                ref="editModal"
                id="editModal"
                tabindex="-1"
                role="dialog"
            >
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Test</h5>
                            <button
                                type="button"
                                class="close"
                                data-dismiss="modal"
                                aria-label="Close"
                            >
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div
                                v-show="
                                    mission.missionType != 'Sniper Assassin'
                                "
                            >
                                <div
                                    class="alert alert-primary"
                                    style="font-size: .8em"
                                >
                                    You can now use a template to auto-fill item
                                    information for common items!
                                    <b>
                                        If you select a template, all
                                        information in "Item Info" and "Notes"
                                        will be replaced with the template's
                                        information.
                                    </b>
                                </div>
                                <h3>Apply Template</h3>
                                <div class="form-group row">
                                    <label
                                        for="template"
                                        class="col sm-2 col-form-label"
                                    >
                                        Template
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
                                Please indicate the following under the notes
                                section when adding items:
                                <ul>
                                    <li>
                                        Any
                                        <b>required</b>
                                        items, such as needing a wrench for
                                        loosening valves (use type
                                        "Requirement")
                                    </li>
                                    <li>
                                        Whether or not the action is
                                        <b>suspicious</b>
                                        when wearing a certain disguise (use
                                        type "Warning")
                                    </li>
                                    <li>
                                        If you feel something
                                        <b>requires more information</b>
                                        , use the "Information" type
                                    </li>
                                    <li>
                                        You may add the in-game description for
                                        items by using the "Description" type
                                        <b>
                                            if there is no name field provided.
                                        </b>
                                    </li>
                                    <li>
                                        Any notes with blank text will not be
                                        added to the node
                                    </li>
                                </ul>
                            </div>
                            <div class="form-group row">
                                <label
                                    for="subgroup"
                                    class="col-sm-2 col-form-label"
                                >
                                    Category
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
                                    Icon
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
                                        Name
                                    </label>
                                    <div class="col-sm-10">
                                        <input
                                            type="text"
                                            name="name"
                                            v-model="currentCategory.name"
                                            class="form-control"
                                        />
                                        <small class="form-text text-muted">
                                            Enter the name as it appears
                                            in-game.
                                        </small>
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
                                        Action
                                    </label>
                                    <div class="col-sm-10">
                                        <input
                                            type="text"
                                            name="action"
                                            v-model="currentCategory.action"
                                            class="form-control"
                                        />
                                        <small class="form-text text-muted">
                                            Enter the action that is performed
                                            (such as "loosen the valve", "turn
                                            on radio", "trigger fire alarm",
                                            etc.)
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
                                        Target
                                    </label>
                                    <div class="col-sm-10">
                                        <input
                                            type="text"
                                            name="target"
                                            v-model="currentCategory.target"
                                            class="form-control"
                                        />
                                        <small class="form-text text-muted">
                                            Who will be poisoned?
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
                                        Type
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
                                                Pickup (Large)
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
                                                Stash (Small)
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
                                        Direction
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
                                                Up Only
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
                                                Up and Down
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
                                                Down Only
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <h3>Notes</h3>
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
                                            Type
                                        </label>
                                        <div class="col-sm-10">
                                            <select
                                                v-model="note.type"
                                                class="form-control"
                                                name="note-type[]"
                                            >
                                                <option value="requirement">
                                                    Requirement
                                                </option>
                                                <option value="warning">
                                                    Warning
                                                </option>
                                                <option value="info">
                                                    Information
                                                </option>
                                                <option value="description">
                                                    Description
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label
                                            for="note-text[]"
                                            class="col-sm-2 col-form-label"
                                        >
                                            Text
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
                                        + Add Another Note
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button
                                type="button"
                                class="btn btn-secondary"
                                data-dismiss="modal"
                            >
                                Close
                            </button>
                            <button
                                type="button"
                                @click="saveMarker"
                                class="btn btn-primary"
                            >
                                Save changes
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {
    LMap,
    LTileLayer,
    LMarker,
    LLayerGroup,
    LTooltip,
    LPopup,
    LPolyline,
    LPolygon,
    LIcon
} from 'vue2-leaflet'
import Vue from 'vue'
import 'leaflet/dist/leaflet.css'

import 'leaflet.pm'
import 'leaflet.pm/dist/leaflet.pm.css'

import CxltToaster from 'cxlt-vue2-toastr'
import 'cxlt-vue2-toastr/dist/css/cxlt-vue2-toastr.css'

import GameButton from '../components/GameButton'
import Modal from '../components/Modal'

Vue.use(CxltToaster)
export default {
    name: 'map-view',
    components: {
        LMap,
        LTileLayer,
        LMarker,
        LLayerGroup,
        LTooltip,
        LPopup,
        LPolyline,
        LPolygon,
        LIcon,
        GameButton,
        Modal
    },
    title() {
        return this.mission ? this.mission.name : 'Loading'
    },
    data() {
        return {
            disguises: [],
            ledges: [],
            foliage: [],
            nodes: null,
            searchableNodes: null,
            currentLayer: 0,
            crs: L.CRS.Simple,
            layerGroups: {},
            categories: {},
            mapLoaded: false,
            hiddenLayers: [],
            searchedItem: null,
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
                vertices: [],
                workingLayers: []
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
                return (
                    'png/mission-thumbnails/' +
                    this.$route.params.slug +
                    '/' +
                    this.mission.slug +
                    '.png'
                )
            }

            if (this.game != null && this.game.slug == 'hitman') {
                return (
                    'jpg/elusive-targets/legacy/' + this.mission.slug + '.jpg'
                )
            }

            return 'jpg/elusive-targets/' + this.mission.slug + '.jpg'
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
                console.log(new Date(data.exp * 1000).getTime() - now.getTime())
                if (new Date(data.exp * 1000).getTime() - now.getTime() > 0)
                    return true
            }
            return false
        }
    },
    methods: {
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
            this.currentLayer = level
        },
        collapsible: function(type, group) {
            return (
                type.name.replace(/ /g, '_') +
                '-' +
                group.name.replace(/ /g, '_')
            )
        },
        editorMenu: function(menu) {
            this.editor.mode = menu
        },
        addMarker: function(event) {
            if (!this.editor.enabled || this.editor.mode !== 'items') return
            this.editor.clickedPoint = event.latlng
            let element = this.$refs.editModal
            //Reset old data
            if (this.currentCategory) {
                this.currentCategory.nodeId = undefined
                this.currentCategory.name = undefined
                this.currentCategory.action = undefined
                this.currentCategory.target = undefined
            }

            this.editor.currentCategory = undefined
            $(this.$refs.subgroupPicker).selectpicker('val', -1)
            $(this.$refs.iconPicker).selectpicker('val', -1)
            $(this.$refs.templatePicker).selectpicker('val', -1)
            this.editor.notes = []
            $(element).modal('show')
        },
        moveMarker: function(event, item) {
            console.log(event)
            if (this.editor.mode === '') {
                //Resetting the position
                event.target.setLatLng([item.latitude, item.longitude])
                return
            }
            item.latitude = event.target.getLatLng().lat
            item.longitude = event.target.getLatLng().lng
            this.editor.currentMarker = item
            $(this.$refs.confirmMoveModal).modal('show')
        },
        editMarker: function(item) {
            this.editor.notes = item.notes
            this.editor.currentCategory = item.type + '|' + item.subgroup
            this.editor.clickedPoint = L.latLng(item.latitude, item.longitude)
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
            this.currentCategory.action = item.target
            this.currentCategory.target = item.target

            $(this.$refs.editModal).modal('show')
        },
        confirmMove: function() {
            var data = new FormData()
            console.log(this.editor.currentMarker)
            data.append('node-id', this.editor.currentMarker.id)
            data.append('latitude', this.editor.currentMarker.latitude)
            data.append('longitude', this.editor.currentMarker.longitude)
            this.$request(true, 'nodes/move', data).then(() =>
                $(this.$refs.confirmMoveModal).modal('hide')
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
                //TODO Make this a method
                if (resp.data.data.approved) {
                    resp.data.data.latLng = L.latLng(
                        resp.data.data.latitude,
                        resp.data.data.longitude
                    )
                    this.layerGroups[
                        resp.data.data.type + '|' + resp.data.data.group
                    ].items.push(resp.data.data)
                }
                $(this.$refs.editModal).modal('hide')
            })
        },
        deleteMarker: function(node) {
            this.$request(false, 'nodes/delete/' + node.id).then(resp => {
                console.log('Node successfully deleted')
                this.layerGroups[node.type + '|' + node.group].items.splice(
                    this.layerGroups[
                        node.type + '|' + node.group
                    ].items.indexOf(node),
                    1
                )
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
        toggleLayer: function(name, hideAll) {
            if (name === '|') {
                this.hiddenLayers = []

                if (hideAll) {
                    this.hiddenLayers = ['Points of Interest|', 'Weapons and Tools|', 'Navigation|']
                }

                return
            }

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
        },
        toggleDraw: function(type) {
            console.log(this.$refs.map.mapObject.pm.Draw)
            if (this.$refs.map.mapObject.pm.Draw[type]._enabled) {
                this.$refs.map.mapObject.pm.disableDraw(type)
            } else {
                this.$refs.map.mapObject.pm.enableDraw(type, {
                    snappable: false
                })
            }
        },
        initDraw: function(e) {
            e.workingLayer.on('pm:vertexadded', e => {
                console.log(e.latlng)
                this.editor.vertices.push([e.latlng.lat, e.latlng.lng])
            })
        },
        pmLayer: function(e) {
            this.editor.workingLayers.push(e.layer)
        },
        endDraw: function(e) {
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
                    this.ledges.push(resp.data.data)
                    this.editor.workingLayers.forEach(el => {
                        this.$refs.map.mapObject.removeLayer(el)
                    })
                    this.editor.workingLayers = []
                })
            } else if (this.editor.mode === 'foliage') {
                this.$request(true, 'foliage', data).then(resp => {
                    this.editor.vertices = []
                    this.foliage.push(resp.data.data)
                    this.editor.workingLayers.forEach(el => {
                        this.$refs.map.mapObject.removeLayer(el)
                    })
                    this.editor.workingLayers = []
                })
            } else if (this.editor.mode === 'disguises') {
                data.append('disguiseId', this.editor.currentDisguise)
                data.append('type', this.editor.disguiseType)
                this.$request(true, 'disguise-areas', data).then(resp => {
                    this.editor.vertices = []
                    this.disguises
                        .find(el => el.id == this.editor.currentDisguise)
                        .areas.push(resp.data.data)
                    this.editor.workingLayers.forEach(el => {
                        this.$refs.map.mapObject.removeLayer(el)
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
            console.log(disguise)
            this.editor.currentDisguise = disguise.id || disguise
            $(this.$refs.disguisePicker).selectpicker(
                'val',
                disguise.id || disguise
            )
            $('#header-disguises')
                .find('.name')
                .click()
        },
        calculateNumber(floor) {
            var count = 0
            for (var [key, val] of Object.entries(this.layerGroups)) {
                if (!this.isLayerHidden(key)) {
                    count += val.items.filter(el => el.level == floor).length
                }
            }
            return count
        },
        searchItem(event) {
            if (event.target.value === '') {
                return
            }

            $('.search-box[data-search="items"]')
                .find('.bootstrap-select')
                .addClass('item-selected')
                .end()
            var item = event.target.value.split(';')
            if (this.isLayerHidden(item[0])) {
                this.toggleLayer(item[0])
            }
            var group = JSON.parse(JSON.stringify(this.layerGroups[item[0]]))
            group.items = group.items.filter(el => el.name == item[1])
            this.searchedItem = group
        },
        hasSearchResults(floor) {
            if (this.searchedItem == null) return false
            return (
                this.searchedItem.items.filter(el => el.level == floor).length >
                0
            )
        },
        isSearchResult(item) {
            if (this.searchedItem == null) return false
            return (
                this.searchedItem.items.filter(el => el.id == item.id).length >
                0
            )
        },
        clearSearch() {
            this.searchedItem = null
            $('.search-box[data-search="items"]')
                .find('.bootstrap-select')
                .removeClass('item-selected')
                .end()
            $(this.$refs.itemSearch).selectpicker('val', '')
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
                if (!this.categories[category.type])
                    this.categories[category.type] = []
                this.categories[category.type].push(category)
                this.layerGroups[category.type + '|' + category.group] =
                    resp.data.nodes[category.type].items[category.group]
            })
            console.log(this.layerGroups)
            this.$nextTick(() => {
                this.$refs.map.mapObject.setView(
                    [
                        Number(this.mission.mapCenterLatitude),
                        Number(this.mission.mapCenterLongitude)
                    ],
                    3
                )
                $('#map-control').append(
                    this.$refs.map.mapObject.zoomControl.getContainer()
                )
                this.mapLoaded = true
                //Is not needed directly at start
                this.$request(false, 'v1/editor/templates').then(resp => {
                    this.editor.templates = resp.data
                    this.$nextTick(() => {
                        $('.selectpicker').selectpicker()
                    })
                })
                this.$request(false, 'v1/editor/icons').then(resp => {
                    this.editor.icons = resp.data
                    this.$nextTick(() => {
                        $(this.$refs.iconPicker).selectpicker('destroy')
                        $(this.$refs.iconPicker).selectpicker()
                    })
                })
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

    /*.leaflet-control-container {
          display: none;
        }*/

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

.leaflet-marker-icon.search-result {
    z-index: 9999 !important;
    background: rgba(255, 0, 60, 0.75);
    padding: 15px !important;
    margin: -33px 0 0 -33px !important;
    border-radius: 50%;
    border: 2px solid #ff003c;
    opacity: 0.85 !important;
    box-sizing: content-box;
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
    border: solid 2px #fff;
    text-align: center;
    margin-bottom: 5px;
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
</style>
