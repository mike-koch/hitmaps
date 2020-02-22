<template>
    <div
        class="content"
        style="background: url('/img/jpg/promo/gm-background.jpg') no-repeat center center fixed; background-size: cover"
    >
        <header class="row" style="margin-top: 200px">
            <div class="col text-center site-header">
                <h1>GRAND FINALS ON FEBRUARY 23</h1>
                <h1>SPONSORED BY</h1>
                <img
                    v-webp
                    src="/img/png/logos/hitmaps.png"
                    class="img-fluid"
                />
                <div class="row" style="margin-top: 20px;" v-if="new Date() > new Date('2020-02-23T23:59:59+00:00')">
                    <div class="col-md-6 col-sm-12 offset-md-3">
                        <div class="embed-responsive embed-responsive-16by9"
                             style="min-height: 310px">
                            <iframe
                                    src="https://player.twitch.tv/?autoplay=false&channel=Frote7"
                                    height="270"
                                    width="50%"
                                    frameborder="0"
                                    scrolling="no"
                                    autoplay="false"
                                    allowfullscreen="true">
                            </iframe>
                        </div>
                    </div>
                </div>
                <h1>{{ $t('interactive-maps-for-hitman') }}</h1>
            </div>
        </header>
        <div
                class="row loading"
                v-if="games.length === 0 && elusiveTargets.length === 0"
        >
            <div class="loader">
                <loader></loader>
            </div>
        </div>
        <div
            class="row dashboard"
            v-if="games.length > 0 || elusiveTargets.length > 0"
        >

            <div
                class="game col-lg"
                v-for="game in games"
                :key="game.id"
                v-bind:style="{
                    backgroundImage:
                        'url(' + game.tileUrl + ')',
                    backgroundSize: 'cover',
                    backgroundPosition: 'center',
                    backgroundRepeat: 'no-repeat'
                }"
            >
                <router-link
                    :to="{ name: 'level-select', params: { slug: game.slug } }"
                >
                    <p>&nbsp;</p>
                    <div class="game-info">
                        <div class="image">
                            <img
                                :src="'/img/game-icons/' + game.icon + '.png'"
                                class="normal img-fluid"
                                :alt="game.type + ' Icon'"
                            />
                            <img
                                :src="
                                    '/img/game-icons/' +
                                        game.icon +
                                        '-inverted.png'
                                "
                                class="inverted img-fluid"
                                :alt="game.type + ' Icon'"
                            />
                        </div>
                        <div class="text">
                            <h2>{{ $t("game-type." + game.type) }}</h2>
                            <h1>{{ game.fullName }}</h1>
                        </div>
                    </div>
                </router-link>
            </div>
            <div class="elusive-target col-lg"
                 id="promo"
                 v-if="showPromo === 1"
                 :style="{
                    backgroundImage:
                        'url(/img/png/promo/gm2.png)',
                    backgroundSize: 'cover',
                    backgroundPosition: 'center',
                    backgroundRepeat: 'no-repeat'
                }">
                <a href="https://discord.gg/yUjhHTr" target="_blank">
                    <p>
                        <img src="/img/png/promo/gm-title.gif" class="img-fluid">
                    </p>
                    <div class="countdown" v-if="new Date(promoStartDate) > new Date()" style="background: rgba(0,0,0,.4)">
                        <img
                                src="/img/game-icons/elusive-target-reminaing-time.png"
                        />
                        <div class="timer not-playable">
                            <div class="target-arrives">{{ (new Date(promoStartDate) > new Date()) ? 'Matches Begin' : '' }}</div>
                            <countdown
                                    class="elusive-countdown"
                                    :date="
                                    new Date(promoStartDate) >
                                    new Date()
                                        ? promoStartDate
                                        : promoEndDate
                                "
                            ></countdown>
                        </div>
                    </div>
                    <div class="game-info">
                        <div class="image">
                            <i class="fab fa-discord fa-3x" style="width: 48px; height: 48px"></i>
                        </div>
                        <div class="text">
                            <h2>Ghost Mode</h2>
                            <h1>Tournament #2</h1>
                        </div>
                        <div
                                onclick="return false;"
                                @click="showRouletteRivalsModal"
                                class="image elusive-notification float-right notification-icon"
                                v-tooltip:left="'More Information'"
                        >
                            <img
                                    src="/img/game-icons/briefing-transparent.png"
                                    class="normal img-fluid"
                                    alt="More Information Icon"
                            />
                            <img
                                    src="/img/game-icons/briefing-inverted.png"
                                    class="inverted img-fluid"
                                    alt="More Information Icon"
                            />
                        </div>
                    </div>
                </a>
            </div>
            <div
                class="elusive-target col-lg"
                v-if="showPromo === 0"
                v-bind:style="{
                    backgroundImage:
                        'url(' +
                        (elusiveTarget != null
                            ? elusiveTarget.tileUrl
                            : '/img/jpg/elusive-targets/coming-soon.jpg') +
                        ')',
                    backgroundSize: 'cover',
                    backgroundPosition: 'center',
                    backgroundRepeat: 'no-repeat'
                }"
            >
                <a
                    :href="elusiveTarget.missionUrl"
                    v-if="elusiveTarget != null"
                >
                    <p>&nbsp;</p>
                    <div class="countdown">
                        <img
                            src="/img/game-icons/elusive-target-reminaing-time.png"
                        />
                        <div
                            class="timer"
                            :class="{
                                'not-playable':
                                    new Date(elusiveTarget.beginningTime) >
                                    new Date()
                            }"
                        >
                            <div class="target-arrives">{{ $t('elusive-target.target-arrives') }}</div>
                            <countdown
                                class="elusive-countdown"
                                :date="
                                    new Date(elusiveTarget.beginningTime) >
                                    new Date()
                                        ? elusiveTarget.beginningTime
                                        : elusiveTarget.endingTime
                                "
                            ></countdown>
                        </div>
                        <img
                            onclick="return false;"
                            @click="showBriefing"
                            src="/img/game-icons/briefing-transparent.png"
                            class="normal img-fluid briefing-icon float-right"
                            :alt="$t('elusive-target.briefing-icon')"
                            v-tooltip:left="$t('elusive-target.mission-briefing')"
                        />
                    </div>
                    <div class="elusive-target-info">
                        <div class="image">
                            <img
                                src="/img/game-icons/elusive-target.png"
                                class="normal img-fluid"
                                :alt="$t('elusive-target-icon')"
                            />
                            <img
                                src="/img/game-icons/elusive-target-inverted.png"
                                class="inverted img-fluid"
                                :alt="$t('elusive-target-icon')"
                            />
                        </div>
                        <div class="text">
                            <h2>{{ $t('game-type.Elusive Target') }}</h2>
                            <h1>
                                {{
                                    elusiveTarget != null
                                        ? elusiveTarget.name
                                        : $t('elusive-target.coming-soon')
                                }}
                            </h1>
                        </div>
                        <div
                            onclick="return false;"
                            @click="showNotificationModal"
                            class="image elusive-notification float-right notification-icon"
                            v-tooltip:left="$t('elusive-target.notifications.manage-notifications')"
                        >
                            <img
                                src="/img/game-icons/notification.png"
                                class="normal img-fluid"
                                :alt="$t('elusive-target.notifications.notification-icon')"
                            />
                            <img
                                src="/img/game-icons/notification-inverted.png"
                                class="inverted img-fluid"
                                :alt="$t('elusive-target.notifications.notification-icon')"
                            />
                        </div>
                    </div>
                </a>
                <a v-else href="#">
                    <p>&nbsp;</p>
                    <div class="elusive-target-info">
                        <div class="image">
                            <img
                                src="/img/game-icons/elusive-target.png"
                                class="normal img-fluid"
                                :alt="$t('elusive-target-icon')"
                            />
                            <img
                                src="/img/game-icons/elusive-target-inverted.png"
                                class="inverted img-fluid"
                                :alt="$t('elusive-target-icon')"
                            />
                        </div>
                        <div class="text">
                            <h2>{{ $t('game-type.Elusive Target') }}</h2>
                            <h1>{{ $t('elusive-target.coming-soon') }}</h1>
                        </div>
                        <div
                            onclick="return false;"
                            @click="showNotificationModal"
                            class="image float-right notification-icon"
                            v-tooltip:left="$t('elusive-target.notifications.manage-notifications')"
                        >
                            <img
                                src="/img/game-icons/notification.png"
                                class="normal img-fluid"
                                :alt="$t('elusive-target.notifications.notification-icon')"
                            />
                            <img
                                src="/img/game-icons/notification-inverted.png"
                                class="inverted img-fluid"
                                :alt="$t('elusive-target.notifications.notification-icon')"
                            />
                        </div>
                    </div>
                </a>
            </div>
            <div
                ref="briefing"
                v-if="elusiveTarget != null"
                class="modal game-modal fade"
                id="briefing-modal"
                tabindex="-1"
                role="dialog"
                aria-labelledby="briefing-modal-label"
                aria-hidden="true"
            >
                <div class="modal-dialog modal-xl" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="briefing-modal-label">
                                {{ elusiveTarget.name }}
                            </h5>
                        </div>
                        <div class="modal-body d-flex flex-column">
                            <div class="row">
                                <div
                                    v-if="
                                        elusiveTarget.videoBriefingUrl != null
                                    "
                                    class="col-xl"
                                >
                                    <div
                                        class="embed-responsive embed-responsive-16by9"
                                    >
                                        <iframe
                                            :src="
                                                elusiveTarget.videoBriefingUrl
                                            "
                                            class="embed-responsive-item"
                                            frameborder="0"
                                            allow="accelerometer; encrypted-media; gyroscope; picture-in-picture"
                                            allowfullscreen
                                        ></iframe>
                                    </div>
                                </div>
                                <div class="col-xl">
                                    <p v-html="elusiveTarget.briefing"></p>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button
                                type="button"
                                class="btn btn-block btn-secondary"
                                data-dismiss="modal"
                            >
                                <img
                                    src="/img/game-icons/modal-close.png"
                                    class="normal img-fluid"
                                    :alt="$t('form.close-icon')"
                                />
                                <img
                                    src="/img/game-icons/modal-close-inverted.png"
                                    class="inverted img-fluid"
                                    :alt="$t('form.close-icon')"
                                />
                                {{ $t('form.close') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div
                ref="notification-modal"
                class="modal game-modal fade"
                id="notification-modal"
                tabindex="-1"
                role="dialog"
                aria-labelledby="notification-modal-label"
                aria-hidden="true"
            >
                <div class="modal-dialog modal-xl" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5
                                class="modal-title"
                                id="notification-modal-label"
                            >
                                {{ $t('elusive-target.notifications.manage-notifications-modal-title') }}
                            </h5>
                        </div>
                        <div class="modal-body d-flex flex-column">
                            <alert type="info">
                                As of <b>January 10, 2020</b>, all traffic from hitman2maps.com is now being redirected
                                to hitmaps.com. Due to this change, all Elusive Target push notification preferences have been <i>cleared</i>. Please
                                re-subscribe if you wish to continue to receive Elusive Target push notifications. We apologize for any inconvenience.
                            </alert>
                            <div id="checking-notification-status">
                                <h6>{{ $t('elusive-target.notifications.checking-if-enabled') }}</h6>
                                <div class="spinner-grow" role="status">
                                    <span class="sr-only">{{ $t('form.loading') }}</span>
                                </div>
                            </div>
                            <div
                                id="notifications-unsupported"
                                style="display: none"
                            >
                                <h6>{{ $t('elusive-target.notifications.unsupported-browser') }}</h6>
                                <ul>
                                    <li>
                                        iOS
                                        <ul>
                                            <li>
                                                <a href="https://itunes.apple.com/us/app/google-chrome/id535886823?mt=8">
                                                    Google Chrome
                                                </a>
                                            </li>
                                            <li>
                                                <a href="https://itunes.apple.com/us/app/firefox-web-browser/id989804926?mt=8">
                                                    Firefox
                                                </a>
                                            </li>
                                            <li>
                                                <a href="https://itunes.apple.com/us/app/opera-touch-web-browser/id1411869974?mt=8">
                                                    Opera
                                                </a>
                                            </li>
                                            <li>
                                                <a href="https://itunes.apple.com/us/app/brave-browser-fast-adblocker/id1052879175">
                                                    Brave
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
                                    <li>
                                        Android
                                        <ul>
                                            <li>
                                                <a href="https://play.google.com/store/apps/details?id=com.android.chrome&hl=en_US">
                                                    Google Chrome
                                                </a>
                                            </li>
                                            <li>
                                                <a href="https://play.google.com/store/apps/details?id=org.mozilla.firefox&hl=en_US">
                                                    Firefox
                                                </a>
                                            </li>
                                            <li>
                                                <a href="https://play.google.com/store/apps/details?id=com.brave.browser&hl=en_US">
                                                    Brave
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
                                    <li>
                                        Desktop
                                        <ul>
                                            <li>
                                                <a href="https://www.google.com/chrome/">
                                                    Google Chrome
                                                </a>
                                            </li>
                                            <li>Microsoft Edge</li>
                                            <li>
                                                <a href="https://www.mozilla.org/en-US/firefox/new/">
                                                    Firefox
                                                </a>
                                            </li>
                                            <li>
                                                <a href="https://www.opera.com/computer">
                                                    Opera
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                            <div id="enrollment-required" style="display: none">
                                <h6>{{ $t('elusive-target.notifications.device-not-enrolled') }}</h6>
                                <p id="error-container"></p>
                            </div>
                            <div
                                id="notifications-blocked"
                                style="display: none"
                            >
                                <h6>{{ $t('elusive-target.notifications.notifications-blocked') }}</h6>
                            </div>
                            <div
                                id="notification-settings"
                                style="display: none"
                            >
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <td><b>{{ $t('elusive-target.notifications.send-me-a-notification-when') }}</b></td>
                                        <td>{{ $t('elusive-target.notifications.new-et') }}</td>
                                        <td>{{ $t('elusive-target.notifications.reactivated-et') }}</td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>
                                            {{ $t('elusive-target.notifications.announced') }}
                                        </td>
                                        <td>
                                            <input type="checkbox"
                                                   class="form-check-input"
                                                   id="elusive-target-coming"
                                                   @change="toggleNotificationState"
                                                   :data-firebase-topic="'hitmaps-' + environment + '-elusive-target-coming'"
                                                   v-model="notifications.new.almostPlayable">
                                        </td>
                                        <td>
                                            <input type="checkbox"
                                                   class="form-check-input"
                                                   id="reactivation-elusive-target-coming"
                                                   @change="toggleNotificationState"
                                                   :data-firebase-topic="'hitmaps-' + environment + '-reactivation-elusive-target-coming'"
                                                   v-model="notifications.reactivation.almostPlayable">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            {{ $t('elusive-target.notifications.playable') }}
                                        </td>
                                        <td>
                                            <input type="checkbox"
                                                   class="form-check-input"
                                                   id="elusive-target-playable"
                                                   @change="toggleNotificationState"
                                                   :data-firebase-topic="'hitmaps-' + environment + '-elusive-target-playable'"
                                                   v-model="notifications.new.becomesPlayable">
                                        </td>
                                        <td>
                                            <input type="checkbox"
                                                   class="form-check-input"
                                                   id="reactivation-elusive-target-playable"
                                                   @change="toggleNotificationState"
                                                   :data-firebase-topic="'hitmaps-' + environment + '-reactivation-elusive-target-playable'"
                                                   v-model="notifications.reactivation.becomesPlayable">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            {{ $t('elusive-target.notifications.7-days') }}
                                        </td>
                                        <td>
                                            <input type="checkbox"
                                                   class="form-check-input"
                                                   id="elusive-target-7"
                                                   @change="toggleNotificationState"
                                                   :data-firebase-topic="'hitmaps-' + environment + '-elusive-target-7'"
                                                   v-model="notifications.new.sevenDays">
                                        </td>
                                        <td>
                                            <input type="checkbox"
                                                   class="form-check-input"
                                                   id="reactivation-elusive-target-7"
                                                   @change="toggleNotificationState"
                                                   :data-firebase-topic="'hitmaps-' + environment + '-reactivation-elusive-target-7'"
                                                   v-model="notifications.reactivation.sevenDays">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            {{ $t('elusive-target.notifications.5-days') }}
                                        </td>
                                        <td>
                                            <input type="checkbox"
                                                   class="form-check-input"
                                                   id="elusive-target-5"
                                                   @change="toggleNotificationState"
                                                   :data-firebase-topic="'hitmaps-' + environment + '-elusive-target-5'"
                                                   v-model="notifications.new.fiveDays">
                                        </td>
                                        <td>
                                            <input type="checkbox"
                                                   class="form-check-input"
                                                   id="reactivation-elusive-target-5"
                                                   @change="toggleNotificationState"
                                                   :data-firebase-topic="'hitmaps-' + environment + '-reactivation-elusive-target-5'"
                                                   v-model="notifications.reactivation.fiveDays">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            {{ $t('elusive-target.notifications.3-days') }}
                                        </td>
                                        <td>
                                            <input type="checkbox"
                                                   class="form-check-input"
                                                   id="elusive-target-3"
                                                   @change="toggleNotificationState"
                                                   :data-firebase-topic="'hitmaps-' + environment + '-elusive-target-3'"
                                                   v-model="notifications.new.threeDays">
                                        </td>
                                        <td>
                                            <input type="checkbox"
                                                   class="form-check-input"
                                                   id="reactivation-elusive-target-3"
                                                   @change="toggleNotificationState"
                                                   :data-firebase-topic="'hitmaps-' + environment + '-reactivation-elusive-target-3'"
                                                   v-model="notifications.reactivation.threeDays">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            {{ $t('elusive-target.notifications.1-day') }}
                                        </td>
                                        <td>
                                            <input type="checkbox"
                                                   class="form-check-input"
                                                   id="elusive-target-1"
                                                   @change="toggleNotificationState"
                                                   :data-firebase-topic="'hitmaps-' + environment + '-elusive-target-1'"
                                                   v-model="notifications.new.oneDay">
                                        </td>
                                        <td>
                                            <input type="checkbox"
                                                   class="form-check-input"
                                                   id="reactivation-elusive-target-1"
                                                   @change="toggleNotificationState"
                                                   :data-firebase-topic="'hitmaps-' + environment + '-reactivation-elusive-target-1'"
                                                   v-model="notifications.reactivation.oneDay">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            {{ $t('elusive-target.notifications.ended') }}
                                        </td>
                                        <td>
                                            <input type="checkbox"
                                                   class="form-check-input"
                                                   id="elusive-target-end"
                                                   @change="toggleNotificationState"
                                                   :data-firebase-topic="'hitmaps-' + environment + '-elusive-target-end'"
                                                   v-model="notifications.new.ended">
                                        </td>
                                        <td>
                                            <input type="checkbox"
                                                   class="form-check-input"
                                                   id="reactivation-elusive-target-end"
                                                   @change="toggleNotificationState"
                                                   :data-firebase-topic="'hitmaps-' + environment + '-reactivation-elusive-target-end'"
                                                   v-model="notifications.reactivation.ended">
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                                <input type="hidden" name="firebase-token" />
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button
                                type="button"
                                class="btn btn-block btn-secondary"
                                id="enroll-button"
                                style="display: none"
                                @click="enroll"
                            >
                                <img
                                    src="/img/game-icons/modal-continue.png"
                                    class="normal img-fluid"
                                    :alt="$t('elusive-target.notifications.enroll-icon')"
                                />
                                <img
                                    src="/img/game-icons/modal-continue-inverted.png"
                                    class="inverted img-fluid"
                                    :alt="$t('elusive-target.notifications.enroll-icon')"
                                />
                                {{ $t('elusive-target.notifications.enroll') }}
                            </button>
                            <button
                                type="button"
                                class="btn btn-block btn-secondary"
                                data-dismiss="modal"
                            >
                                <img
                                    src="/img/game-icons/modal-close.png"
                                    class="normal img-fluid"
                                    :alt="$t('form.close-icon')"
                                />
                                <img
                                    src="/img/game-icons/modal-close-inverted.png"
                                    class="inverted img-fluid"
                                    :alt="$t('form.close-icon')"
                                />
                                {{ $t('form.close') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <modal modal-title="Ghost Mode Tournament #2"
               id="roulette-rivals-modal"
               tabindex="-1"
               dismissable>
            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <div class="embed-responsive embed-responsive-16by9"
                         style="min-height: 310px">
                        <iframe src="https://www.youtube.com/embed/c6q4hgt0fiE"
                                class="embed-responsive-item"
                                frameborder="0"
                                allow="accelerometer; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen
                        ></iframe>
                    </div>
                </div>
                <div class="col-md-6 col-sm-12">
                    <p>
                        The second Ghost Mode Tournament, ran by Frote's Speedrun Community and sponsored by HITMAPS&trade;, is coming up!
                    </p>
                    <p>
                        This double-elimination tournament is open to all platforms (PC, PS4, XB1) and is a separate tournament per platform.
                        Each match is a best-of 3, except for the grand final which is best-of 5.
                    </p>
                    <alert type="info">Matches run from <b>February 7</b> to <b>February 23</b>!</alert>
                </div>
            </div>
            <div class="modal-header" style="padding-left: 0">
                <h5><i class="fab fa-twitch"></i> Competitors Currently Streaming</h5>
            </div>
            <div class="streams">
                <div class="row">
                    <div class="col-md-6 col-sm-12 twitch-stream featured">
                        <iframe
                                src="https://player.twitch.tv/?autoplay=false&channel=MrMike227"
                                height="270"
                                width="100%"
                                frameborder="0"
                                scrolling="no"
                                autoplay="false"
                                allowfullscreen="true">
                        </iframe>
                        <p><i class="fas fa-award"></i> MrMike227 (Creator of HITMAPS™️)</p>
                    </div>
                    <div v-for="stream in streams" class="col-md-6 col-sm-12 twitch-stream">
                        <iframe
                                :src="'https://player.twitch.tv/?autoplay=false&channel=' + stream.user_name"
                                height="270"
                                width="100%"
                                frameborder="0"
                                scrolling="no"
                                autoplay="false"
                                allowfullscreen="true">
                        </iframe>
                        <p>{{ stream.user_name }}</p>
                    </div>
                </div>
            </div>
        </modal>
        <input type="hidden" name="notification-environment" />
        <div class="patreon" style="display: none">
            <div class="row intro">
                <div class="col-xs-12">
                    <h1>
                    <span class="fa-stack">
                        <i class="fas fa-square fa-stack-2x"></i>
                        <i class="fab fa-patreon fa-stack-1x fa-inverse"></i>
                    </span>
                        {{ $t('patreon.patreon-supporters') }}
                    </h1>
                    {{ $t('patreon.patreon-supporters-description') }}
                </div>
            </div>
            <div class="row">
                <div class="col-md-3 col-sm-6 patron">
                    <img src="https://media.hitmaps.com/patrons/mow.jpg" class="img-fluid" alt="Mow's Profile Photo">
                    <p><b>Mow</b></p>
                    <p>Master Assassin</p>
                </div>
            </div>
            <div class="row">
                <div class="col" style="text-align: right">
                    <a href="https://www.patreon.com/mike_koch" class="btn patreon-button">
                        <i class="fab fa-patreon"></i>
                        Become a Patron
                    </a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Countdown from '../components/Countdown.vue'
import Loader from '../components/Loader.vue'
import CxltToaster from 'cxlt-vue2-toastr'
import 'cxlt-vue2-toastr/dist/css/cxlt-vue2-toastr.css'
import Vue from 'vue'
import Modal from "../components/Modal";
import Alert from "../components/Alert";

Vue.use(CxltToaster)
export default {
    name: 'home',
    title: 'Home',
    components: {
        Alert,
        Modal,
        Countdown,
        Loader
    },
    data() {
        return {
            showPromo: 1,
            promoStartDate: '2020-02-07T09:00:00+00:00',
            promoEndDate: '2020-02-23T22:59:59+00:00',
            games: [],
            elusiveTargets: [],
            activeElusiveIndex: 0,
            elusiveTarget: null,
            environment: null,
            token: null,
            notifications: {
                new: {
                    almostPlayable: false,
                    becomesPlayable: false,
                    sevenDays: false,
                    fiveDays: false,
                    threeDays: false,
                    oneDay: false,
                    ended: false
                },
                reactivation: {
                    almostPlayable: false,
                    becomesPlayable: false,
                    sevenDays: false,
                    fiveDays: false,
                    threeDays: false,
                    oneDay: false,
                    ended: false
                }
            },
            previousNotificationsState: {
                new: {
                    almostPlayable: false,
                    becomesPlayable: false,
                    sevenDays: false,
                    fiveDays: false,
                    threeDays: false,
                    oneDay: false,
                    ended: false
                },
                reactivation: {
                    almostPlayable: false,
                    becomesPlayable: false,
                    sevenDays: false,
                    fiveDays: false,
                    threeDays: false,
                    oneDay: false,
                    ended: false
                }
            },
            streams: []
        }
    },
    methods: {
        saveGameData() {
            //this.$store.commit("SET_GAME", )
        },
        showRouletteRivalsModal(e) {
            e.preventDefault();
            $('#roulette-rivals-modal').modal('show')
        },
        showBriefing(e) {
            e.preventDefault()
            $(this.$refs.briefing).modal('show')
        },
        showNotificationModal(e) {
            e.preventDefault()
            const that = this
            $(this.$refs['notification-modal']).modal('show')
            if (
                H2MAPS_FIREBASE.messagingSupported !== undefined &&
                !H2MAPS_FIREBASE.messagingSupported
            ) {
                $('#notifications-unsupported').show()
                $('#checking-notification-status').hide()
                return false
            }

            H2MAPS_FIREBASE.checkIfEnrolled(function(enrollmentStatus) {
                if (enrollmentStatus === null) {
                    $('#checking-notification-status').hide()
                    $('#enrollment-required').show()
                    $('#enroll-button').show()
                } else if (
                    enrollmentStatus === H2MAPS_FIREBASE.RESULT.FAILURE
                ) {
                    // Notifications are blocked
                    $('#notifications-blocked').show()
                    $('#checking-notification-status').hide()
                    $('#enroll-button').hide()
                } else {
                    that.token = enrollmentStatus
                    $('input[name="firebase-token"]').val(enrollmentStatus)
                    $('#checking-notification-status').hide()
                    updateCheckboxState(
                        that.token,
                        that.notifications,
                        that.previousNotificationsState,
                        that.environment
                    )
                    $('#notification-settings').show()
                    $('#enroll-button').hide()
                }
            })

            return false
        },
        enroll() {
            requestPermission(0)
        },
        toggleNotificationState() {
            let sendRequest = false;
            let requestType = '';
            let subscribing = false;
            //region New
            if (this.notifications.new.almostPlayable !==
                this.previousNotificationsState.new.almostPlayable) {
                sendRequest = true;
                requestType = 'elusive-target-coming';
                subscribing = this.notifications.new.almostPlayable;
            }
            if (this.notifications.new.becomesPlayable !==
                this.previousNotificationsState.new.becomesPlayable) {
                sendRequest = true;
                requestType = 'elusive-target-playable';
                subscribing = this.notifications.new.becomesPlayable;
            }
            if (this.notifications.new.sevenDays !==
                this.previousNotificationsState.new.sevenDays) {
                sendRequest = true;
                requestType = 'elusive-target-7';
                subscribing = this.notifications.new.sevenDays;
            }
            if (this.notifications.new.fiveDays !==
                this.previousNotificationsState.new.fiveDays) {
                sendRequest = true;
                requestType = 'elusive-target-5';
                subscribing = this.notifications.new.fiveDays;
            }
            if (this.notifications.new.threeDays !==
                this.previousNotificationsState.new.threeDays) {
                sendRequest = true;
                requestType = 'elusive-target-3';
                subscribing = this.notifications.new.threeDays;
            }
            if (this.notifications.new.oneDay !==
                this.previousNotificationsState.new.oneDay) {
                sendRequest = true;
                requestType = 'elusive-target-1';
                subscribing = this.notifications.new.oneDay;
            }
            if (this.notifications.new.ended !==
                this.previousNotificationsState.new.ended) {
                sendRequest = true;
                requestType = 'elusive-target-end';
                subscribing = this.notifications.new.ended;
            }
            //endregion
            //region Reactivation
            if (this.notifications.reactivation.almostPlayable !==
                this.previousNotificationsState.reactivation.almostPlayable) {
                sendRequest = true;
                requestType = 'reactivation-elusive-target-coming';
                subscribing = this.notifications.reactivation.almostPlayable;
            }
            if (this.notifications.reactivation.becomesPlayable !==
                this.previousNotificationsState.reactivation.becomesPlayable) {
                sendRequest = true;
                requestType = 'reactivation-elusive-target-playable';
                subscribing = this.notifications.reactivation.becomesPlayable;
            }
            if (this.notifications.reactivation.sevenDays !==
                this.previousNotificationsState.reactivation.sevenDays) {
                sendRequest = true;
                requestType = 'reactivation-elusive-target-7';
                subscribing = this.notifications.reactivation.sevenDays;
            }
            if (this.notifications.reactivation.fiveDays !==
                this.previousNotificationsState.reactivation.fiveDays) {
                sendRequest = true;
                requestType = 'reactivation-elusive-target-5';
                subscribing = this.notifications.reactivation.fiveDays;
            }
            if (this.notifications.reactivation.threeDays !==
                this.previousNotificationsState.reactivation.threeDays) {
                sendRequest = true;
                requestType = 'reactivation-elusive-target-3';
                subscribing = this.notifications.reactivation.threeDays;
            }
            if (this.notifications.reactivation.oneDay !==
                this.previousNotificationsState.reactivation.oneDay) {
                sendRequest = true;
                requestType = 'reactivation-elusive-target-1';
                subscribing = this.notifications.reactivation.oneDay;
            }
            if (this.notifications.reactivation.ended !==
                this.previousNotificationsState.reactivation.ended) {
                sendRequest = true;
                requestType = 'reactivation-elusive-target-end';
                subscribing = this.notifications.reactivation.ended;
            }
            //endregion

            let data = new FormData()
            let token = $('input[name="firebase-token"]').val()
            let topic = 'hitmaps-' + this.environment + '-' + requestType
            data.append('token', token)
            data.append('state', subscribing ? 'SUBSCRIBING' : 'UNSUBSCRIBING')
            data.append('topic', topic)
            if (sendRequest) {
                this.$request(true, 'notifications', data).then(resp => {
                    this.$toast.success({
                        title: 'Changes Saved',
                        message: 'Notification preferences updated!'
                    })
                    window.localStorage.setItem(
                        token + '|' + topic,
                        subscribing ? '1' : '0'
                    )
                    //region New
                    this.previousNotificationsState.new.almostPlayable = this.notifications.new.almostPlayable;
                    this.previousNotificationsState.new.becomesPlayable = this.notifications.new.becomesPlayable;
                    this.previousNotificationsState.new.sevenDays = this.notifications.new.sevenDays;
                    this.previousNotificationsState.new.fiveDays = this.notifications.new.fiveDays;
                    this.previousNotificationsState.new.threeDays = this.notifications.new.threeDays;
                    this.previousNotificationsState.new.oneDay = this.notifications.new.oneDay;
                    this.previousNotificationsState.new.ended = this.notifications.new.ended;
                    //endregion
                    //region Reactivation
                    this.previousNotificationsState.reactivation.almostPlayable = this.notifications.reactivation.almostPlayable;
                    this.previousNotificationsState.reactivation.becomesPlayable = this.notifications.reactivation.becomesPlayable;
                    this.previousNotificationsState.reactivation.sevenDays = this.notifications.reactivation.sevenDays;
                    this.previousNotificationsState.reactivation.fiveDays = this.notifications.reactivation.fiveDays;
                    this.previousNotificationsState.reactivation.threeDays = this.notifications.reactivation.threeDays;
                    this.previousNotificationsState.reactivation.oneDay = this.notifications.reactivation.oneDay;
                    this.previousNotificationsState.reactivation.ended = this.notifications.reactivation.ended;
                    //endregion
                })
            }
        }
    },
    created: function() {
        this.$http.get(this.$domain + '/api/web/home').then(resp => {
            this.games = resp.data.games
            this.elusiveTargets = resp.data.elusiveTargets
            this.environment = resp.data.environment
            $('input[name="notification-environment"]').val(this.environment)

            if (this.elusiveTargets.length > 0) {
                this.elusiveTarget = this.elusiveTargets[0]
            }

            var that = this;
            /*setInterval(function() {
                if (++that.activeElusiveIndex >= that.elusiveTargets.length) {
                    that.activeElusiveIndex = 0
                }

                that.elusiveTarget =
                    that.elusiveTargets[that.activeElusiveIndex]
            }, 10000);*/
            setInterval(() => {
                this.showPromo = !!this.showPromo ? 0 : 1;
            }, 5000);
        });
        this.$http.get(this.$domain + '/api/twitch/current-streams').then(resp => {
            let whitelistedStreams = [
                'zeekomkommer69',
                'supremecommanderike',
                'camthechest',
                'pokeredface456',
                'sheikthegeek',
                'mulletpride',
                'bigdavedmr',
                'mungadungalis',
                'gkpunk',
                'adoriaclub47',
                'foppeh',
                'mrmike227',
                'ibbe040',
                'vortigauntblade',
                'tryagain_later',
                'a_chicken_chicken',
                'yellowzr1',
                'chubbydarksoul',
                'speedsterrunner217',
                'chaos_agent_45',
                'frote7',
                'double_eagle',
                'some_random_person',
                'in4fun',
                'devilvendetta',
                'srarkady54',
                'yannini',
                'bongo_mann',
                'davidredsox',
                'gulitox',
                'skulkwah',
                'dribbleondosfm',
                'johnnyaxxx',
                'mendietinha',
                'ninja_frosk',
                'the_hermit_crab',
                'sky1yyy',
                'silversurfers1',
                'barg_11',
                'therealfuzk',
                'karma4d'
            ];

            let streams = resp.data.data;

            let filteredStreams = [];
            streams.forEach(stream => {
                // Don't display myself twice
                if (stream['user_name'] === 'MrMike227') {
                    return;
                }

                let streamTitle = stream.title.toLowerCase();
                if (streamTitle.includes('ghost') && streamTitle.includes('mode') && whitelistedStreams.includes(stream['user_name'].toLowerCase())) {
                    filteredStreams.push(stream);
                }
            });

            this.streams = filteredStreams;
        })
    }
}

function requestPermission(retryCount) {
    var $enrollmentRequiredDiv = $('#enrollment-required')
    if (retryCount > 5) {
        $enrollmentRequiredDiv
            .find('#error-container')
            .text(
                'Unfortunately, we were unable to receive permission from your device after five attempts. Please try again later or post a message on the HITMAN forums or on the HITMAN 2 Maps Discord via the links in the footer.'
            )
    }

    H2MAPS_FIREBASE.requestPermission(function(result) {
        if (result === H2MAPS_FIREBASE.RESULT.ERROR) {
            // An unrelated error occurred. Try enrolling again.
            $enrollmentRequiredDiv
                .find('#error-container')
                .text(
                    'Unfortunately, an error occurred when trying to enroll your device. You may try enrolling again by clicking the "Enroll" button.'
                )
            return
        }
        if (result === H2MAPS_FIREBASE.RESULT.FAILURE) {
            return requestPermission(retryCount + 1)
        }

        // Enrollment successful
        $enrollmentRequiredDiv.hide()
        $('input[name="firebase-token"]').val(result)
        $('#notification-settings').show()
        $('#enroll-button').hide()
    })
}

function updateCheckboxState(
    token,
    notifications,
    previousNotificationsState,
    environment
) {
    //region New
    notifications.new.almostPlayable =
        window.localStorage.getItem(
            token + '|hitmaps-' + environment + '-elusive-target-coming'
        ) === '1';
    notifications.new.becomesPlayable =
        window.localStorage.getItem(
            token + '|hitmaps-' + environment + '-elusive-target-playable'
        ) === '1';
    notifications.new.sevenDays =
        window.localStorage.getItem(
            token + '|hitmaps-' + environment + '-elusive-target-7'
        ) === '1';
    notifications.new.fiveDays =
        window.localStorage.getItem(
            token + '|hitmaps-' + environment + '-elusive-target-5'
        ) === '1';
    notifications.new.threeDays =
        window.localStorage.getItem(
            token + '|hitmaps-' + environment + '-elusive-target-3'
        ) === '1';
    notifications.new.oneDay =
        window.localStorage.getItem(
            token + '|hitmaps-' + environment + '-elusive-target-1'
        ) === '1';
    notifications.new.ended =
        window.localStorage.getItem(
            token + '|hitmaps-' + environment + '-elusive-target-end'
        ) === '1';
    //endregion
    //region Reactivation
    notifications.reactivation.almostPlayable =
        window.localStorage.getItem(
            token + '|hitmaps-' + environment + '-reactivation-elusive-target-coming'
        ) === '1';
    notifications.reactivation.becomesPlayable =
        window.localStorage.getItem(
            token + '|hitmaps-' + environment + '-reactivation-elusive-target-playable'
        ) === '1';
    notifications.reactivation.sevenDays =
        window.localStorage.getItem(
            token + '|hitmaps-' + environment + '-reactivation-elusive-target-7'
        ) === '1';
    notifications.reactivation.fiveDays =
        window.localStorage.getItem(
            token + '|hitmaps-' + environment + '-reactivation-elusive-target-5'
        ) === '1';
    notifications.reactivation.threeDays =
        window.localStorage.getItem(
            token + '|hitmaps-' + environment + '-reactivation-elusive-target-3'
        ) === '1';
    notifications.reactivation.oneDay =
        window.localStorage.getItem(
            token + '|hitmaps-' + environment + '-reactivation-elusive-target-1'
        ) === '1';
    notifications.reactivation.ended =
        window.localStorage.getItem(
            token + '|hitmaps-' + environment + '-reactivation-elusive-target-end'
        ) === '1';
    //endregion

    //region New
    previousNotificationsState.new.almostPlayable = notifications.new.almostPlayable;
    previousNotificationsState.new.becomesPlayable = notifications.new.becomesPlayable;
    previousNotificationsState.new.sevenDays = notifications.new.sevenDays;
    previousNotificationsState.new.fiveDays = notifications.new.fiveDays;
    previousNotificationsState.new.threeDays = notifications.new.threeDays;
    previousNotificationsState.new.oneDay = notifications.new.oneDay;
    previousNotificationsState.new.ended = notifications.new.ended;
    //endregion
    //region Reactivation
    previousNotificationsState.reactivation.almostPlayable = notifications.reactivation.almostPlayable;
    previousNotificationsState.reactivation.becomesPlayable = notifications.reactivation.becomesPlayable;
    previousNotificationsState.reactivation.sevenDays = notifications.reactivation.sevenDays;
    previousNotificationsState.reactivation.fiveDays = notifications.reactivation.fiveDays;
    previousNotificationsState.reactivation.threeDays = notifications.reactivation.threeDays;
    previousNotificationsState.reactivation.oneDay = notifications.reactivation.oneDay;
    previousNotificationsState.reactivation.ended = notifications.reactivation.ended;
    //endregion
}
</script>
<style lang="scss" scoped>
.ghost-mode-trailer {
    video {
        position: fixed;
        left: 0;
        top: -300px;
        min-width: 100%;
        min-height: 100%;
        height: inherit;
    }
}

@media (min-width: 768px) {

}

@media (max-width: 767px) {
    .ghost-mode-trailer {
        video {
            top: -400px;
        }
        /*display: none;

        img {
            display: block;
            position: fixed;
            left: 0;
            top: 0;
            min-width: 100%;
            min-height: 100%;
        }*/
    }
}

.embed-responsive-16by9::before {
    padding-top: 21.25%;
}

header {
    /*margin-top: 450px;*/

    .site-header {
        margin: 0 20px;

        h1 {
            margin-top: 20px;
        }

        img {
            max-height: 100px;
        }
    }
}

@media (min-width: 992px) {
    .game,
    .elusive-target {
        height: 500px;
    }
}

@media (max-width: 991px) {
    .game,
    .elusive-target {
        height: 300px;
        margin-bottom: 20px;
    }
}

.loading {
    .loader {
        margin: 100px auto 0;
    }
}

.dashboard {
    margin: 40px;

    .game,
    .elusive-target {
        display: flex;
        flex-direction: column;
        color: white;
        margin-left: 3px;
        margin-right: 3px;

        &:hover {
            .game-info,
            .elusive-target-info {
                color: white;
                background: rgb(252, 0, 59);

                h2 {
                    color: white;
                }

                .image img {
                    &.normal {
                        display: none;
                    }

                    &.inverted {
                        display: block;
                    }
                }
            }

            box-shadow: 2px 2px 2px #000;
        }

        .game-info,
        .elusive-target-info {
            padding: 15px;
            background: white;
            color: #000;
            text-shadow: none;

            h2 {
                color: #ff003c;
            }

            .image {
                display: inline-block;
                vertical-align: top;
                margin-right: 5px;

                &.elusive-notification {
                    margin-right: 0;
                }

                img {
                    height: 48px;
                    width: 48px;

                    &.normal {
                        display: block;
                    }

                    &.inverted {
                        display: none;
                    }
                }
            }

            .text {
                display: inline-block;
                text-transform: uppercase;

                h1 {
                    font-size: 1.5rem;
                    margin-bottom: 0;
                }

                h2 {
                    font-size: 1rem;
                    margin-bottom: 0;
                }
            }
        }
    }

    .game > a,
    .elusive-target > a {
        display: flex;
        flex-direction: column;
        height: 100%;
        margin-left: -15px;
        margin-right: -15px;
        text-decoration: none;

        > p:first-child {
            flex-grow: 1;
        }
    }

    .elusive-target {
        .countdown {
            padding: 15px 15px 0;
            padding-bottom: 1rem;

            img {
                width: 48px;
                height: 48px;
                margin-right: 5px;
            }

            .timer {
                display: inline-block;
                vertical-align: middle;

                .target-arrives {
                    color: #fff;
                    line-height: 0;
                    display: none;
                }

                .elusive-countdown {
                    color: #fef30a;
                    font-size: 2rem;
                    vertical-align: middle;
                    font-weight: 600;
                    text-transform: uppercase;
                }

                &.not-playable {
                    vertical-align: bottom;

                    .target-arrives {
                        display: block;
                        text-transform: uppercase;
                    }

                    .elusive-countdown {
                        line-height: 33px;
                        margin-top: 7px;
                    }
                }
            }
        }
    }

    .one-year {
        /*background-color: rgba(0,0,0,.5);*/
        background-repeat: no-repeat;
        background-position: center;
        background-image: url('/img/jpg/one-year/miami.jpg');
        background-attachment: fixed;
        background-size: 150%;
        margin-left: 3px;
        margin-right: 3px;
        padding-left: 0;
        padding-right: 0;
        text-align: center;
        border: 5px solid #fff;

        a {
            background-color: rgba(0,0,0,.5);
            width: 100%;
            display: block;
            font-size: 2rem;
            color: white;
            padding-top: 20px;
            padding-bottom: 20px;
            text-shadow: 2px 2px #000;
        }
    }
}

.game-modal {
    .modal-content {
        background: transparent;
        border: none;

        .modal-header {
            font-family: 'nimbus_sans_lbold', sans-serif;
            text-transform: uppercase;
            border-bottom: none;
            border-radius: 0;

            background: #fff;

            h5 {
                font-size: 1.5rem;
            }
        }

        .modal-body {
            background: #fff;
        }

        .modal-footer {
            border-top: none;
            padding-left: 0;
            padding-right: 0;
            padding-top: 10px;
            display: block;

            > :not(:first-child) {
                margin: 0;
            }

            #enroll-button {
                margin-bottom: 10px;
            }

            .btn-block {
                border-radius: 0;
                text-transform: uppercase;
                background: #fff;
                color: #000;
                font-family: 'nimbus_sans_lbold', sans-serif;
                text-align: left;
                font-size: 1.3rem;
                transition: none;

                img {
                    max-height: 32px;
                    max-width: 32px;
                    vertical-align: top;

                    &.normal {
                        display: inline-block;
                    }

                    &.inverted {
                        display: none;
                    }
                }

                &:hover {
                    background: #ff003c;
                    color: #fff;

                    img {
                        &.normal {
                            display: none;
                        }

                        &.inverted {
                            display: inline-block;
                        }
                    }
                }
            }
        }
    }
}

#notification-settings {
    td {
        text-align: center;

        .form-check-input {
            margin-left: 0;
        }

        &:first-child {
            text-align: left;
        }
    }
}

.patreon {
    background: #fff;
    padding: 40px;

    .intro {
        margin-bottom: 30px;

        h1 {
            font-family: 'nimbus_sans_lbold', sans-serif;
            text-transform: uppercase;
        }

        i.fa-square {
            color: #e7705c;
        }
    }

    .patron {
        text-align: center;

        img {
            max-height: 128px;
            max-width: 128px;
        }

        p {
            margin-bottom: 0;
        }
    }

    .patreon-button {
        margin-top: 30px;
        border-radius: 0;
        background: #e7705c;
        color: #fff;

        &:hover {
            background: #cd685c;
        }
    }
}

.streams {
    .twitch-stream {
        padding: 10px;

        &.featured {
            background: #ffffcc;
        }
    }
}
</style>
