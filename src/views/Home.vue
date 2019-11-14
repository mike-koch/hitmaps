<template>
    <div
        v-webp
        class="content"
        style="background: url('/img/jpg/backgrounds/loading.jpg') no-repeat center center fixed; background-size: cover"
    >
        <header class="row">
            <div class="col text-center site-header">
                <img
                    v-webp
                    src="/img/png/logos/hitmaps.png"
                    class="img-fluid"
                />
                <h1>{{ $t('interactive-maps-for-hitman') }}</h1>
            </div>
        </header>
        <div class="row dashboard">
            <div class="col one-year">
                <a href="/one-year">
                    HITMAPS: One Year Later - Learn About the Past, Present, and Future of the Site
                </a>
            </div>
        </div>
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
                v-webp
                v-bind:style="{
                    backgroundImage:
                        'url(/img/jpg/backgrounds/' + game.slug + '.jpg)',
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
                        'url(/img/png/promo/speedrun-competition3.png)',
                    backgroundSize: 'cover',
                    backgroundPosition: 'center',
                    backgroundRepeat: 'no-repeat'
                }">
                <a href="https://discord.gg/X2B8myT" target="_blank">
                    <p>&nbsp;</p>
                    <div class="countdown">
                        <img
                                src="/img/game-icons/elusive-target-reminaing-time.png"
                        />
                        <div class="timer not-playable">
                            <div class="target-arrives">{{ (new Date(promoStartDate) > new Date()) ? 'Event Begins' : 'Event Ends' }}</div>
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
                            <h2>Frote7's</h2>
                            <h1>Speedrun Competition #3</h1>
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
                                <h6>{{ $t('elusive-target.notifications.send-me-a-notification-when') }}</h6>
                                <div class="custom-control custom-checkbox">
                                    <input
                                        type="checkbox"
                                        class="custom-control-input"
                                        id="elusive-target-coming"
                                        @change="toggleNotificationState"
                                        :data-firebase-topic="
                                            environment +
                                                '-elusive-target-coming'
                                        "
                                        v-model="notifications.almostPlayable"
                                    />
                                    <label class="custom-control-label" for="elusive-target-coming">
                                        {{ $t('elusive-target.notifications.announced') }}
                                    </label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input
                                        type="checkbox"
                                        class="custom-control-input"
                                        id="elusive-target-playable"
                                        @change="toggleNotificationState"
                                        :data-firebase-topic="
                                            environment +
                                                '-elusive-target-playable'
                                        "
                                        v-model="notifications.becomesPlayable"
                                    />
                                    <label class="custom-control-label" for="elusive-target-playable">
                                        {{ $t('elusive-target.notifications.playable') }}
                                    </label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input
                                        type="checkbox"
                                        class="custom-control-input"
                                        id="elusive-target-7"
                                        @change="toggleNotificationState"
                                        :data-firebase-topic="
                                            environment + '-elusive-target-7'
                                        "
                                        v-model="notifications.sevenDays"
                                    />
                                    <label class="custom-control-label" for="elusive-target-7">
                                        {{ $t('elusive-target.notifications.7-days') }}
                                    </label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input
                                        type="checkbox"
                                        class="custom-control-input"
                                        id="elusive-target-5"
                                        @change="toggleNotificationState"
                                        :data-firebase-topic="
                                            environment + '-elusive-target-5'
                                        "
                                        v-model="notifications.fiveDays"
                                    />
                                    <label class="custom-control-label" for="elusive-target-5">
                                        {{ $t('elusive-target.notifications.5-days') }}
                                    </label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input
                                        type="checkbox"
                                        class="custom-control-input"
                                        id="elusive-target-3"
                                        @change="toggleNotificationState"
                                        :data-firebase-topic="
                                            environment + '-elusive-target-3'
                                        "
                                        v-model="notifications.threeDays"
                                    />
                                    <label class="custom-control-label" for="elusive-target-3">
                                        {{ $t('elusive-target.notifications.3-days') }}
                                    </label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input
                                        type="checkbox"
                                        class="custom-control-input"
                                        id="elusive-target-1"
                                        @change="toggleNotificationState"
                                        :data-firebase-topic="
                                            environment + '-elusive-target-1'
                                        "
                                        v-model="notifications.oneDay"
                                    />
                                    <label class="custom-control-label" for="elusive-target-1">
                                        {{ $t('elusive-target.notifications.1-day') }}
                                    </label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input
                                        type="checkbox"
                                        class="custom-control-input"
                                        id="elusive-target-end"
                                        @change="toggleNotificationState"
                                        :data-firebase-topic="
                                            environment + '-elusive-target-end'
                                        "
                                        v-model="notifications.ended"
                                    />
                                    <label class="custom-control-label" for="elusive-target-end">
                                        {{ $t('elusive-target.notifications.ended') }}
                                    </label>
                                </div>
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
        <input type="hidden" name="notification-environment" />
    </div>
</template>

<script>
import Countdown from '../components/Countdown.vue'
import Loader from '../components/Loader.vue'
import CxltToaster from 'cxlt-vue2-toastr'
import 'cxlt-vue2-toastr/dist/css/cxlt-vue2-toastr.css'
import Vue from 'vue'

Vue.use(CxltToaster)
export default {
    name: 'home',
    title: 'Home',
    components: {
        Countdown,
        Loader
    },
    data() {
        return {
            showPromo: 1,
            promoStartDate: '2019-11-15T11:00:00+00:00',
            promoEndDate: '2019-11-24T22:59:59+00:00',
            games: [],
            elusiveTargets: [],
            activeElusiveIndex: 0,
            elusiveTarget: null,
            environment: null,
            token: null,
            notifications: {
                almostPlayable: false,
                becomesPlayable: false,
                sevenDays: false,
                fiveDays: false,
                threeDays: false,
                oneDay: false,
                ended: false
            },
            previousNotificationsState: {
                almostPlayable: false,
                becomesPlayable: false,
                sevenDays: false,
                fiveDays: false,
                threeDays: false,
                oneDay: false,
                ended: false
            }
        }
    },
    methods: {
        saveGameData() {
            //this.$store.commit("SET_GAME", )
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
            let sendRequest = false
            let requestType = ''
            let subscribing = false
            if (
                this.notifications.almostPlayable !==
                this.previousNotificationsState.almostPlayable
            ) {
                sendRequest = true
                requestType = 'elusive-target-coming'
                subscribing = this.notifications.almostPlayable
            }
            if (
                this.notifications.becomesPlayable !==
                this.previousNotificationsState.becomesPlayable
            ) {
                sendRequest = true
                requestType = 'elusive-target-playable'
                subscribing = this.notifications.becomesPlayable
            }
            if (
                this.notifications.sevenDays !==
                this.previousNotificationsState.sevenDays
            ) {
                sendRequest = true
                requestType = 'elusive-target-7'
                subscribing = this.notifications.sevenDays
            }
            if (
                this.notifications.fiveDays !==
                this.previousNotificationsState.fiveDays
            ) {
                sendRequest = true
                requestType = 'elusive-target-5'
                subscribing = this.notifications.fiveDays
            }
            if (
                this.notifications.threeDays !==
                this.previousNotificationsState.threeDays
            ) {
                sendRequest = true
                requestType = 'elusive-target-3'
                subscribing = this.notifications.threeDays
            }
            if (
                this.notifications.oneDay !==
                this.previousNotificationsState.oneDay
            ) {
                sendRequest = true
                requestType = 'elusive-target-1'
                subscribing = this.notifications.oneDay
            }
            if (
                this.notifications.ended !==
                this.previousNotificationsState.ended
            ) {
                sendRequest = true
                requestType = 'elusive-target-end'
                subscribing = this.notifications.ended
            }

            let data = new FormData()
            let token = $('input[name="firebase-token"]').val()
            let topic = this.environment + '-' + requestType
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
                    this.previousNotificationsState.almostPlayable = this.notifications.almostPlayable
                    this.previousNotificationsState.becomesPlayable = this.notifications.becomesPlayable
                    this.previousNotificationsState.sevenDays = this.notifications.sevenDays
                    this.previousNotificationsState.fiveDays = this.notifications.fiveDays
                    this.previousNotificationsState.threeDays = this.notifications.threeDays
                    this.previousNotificationsState.oneDay = this.notifications.oneDay
                    this.previousNotificationsState.ended = this.notifications.ended
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
    notifications.almostPlayable =
        window.localStorage.getItem(
            token + '|' + environment + '-elusive-target-coming'
        ) === '1'
    notifications.becomesPlayable =
        window.localStorage.getItem(
            token + '|' + environment + '-elusive-target-playable'
        ) === '1'
    notifications.sevenDays =
        window.localStorage.getItem(
            token + '|' + environment + '-elusive-target-7'
        ) === '1'
    notifications.fiveDays =
        window.localStorage.getItem(
            token + '|' + environment + '-elusive-target-5'
        ) === '1'
    notifications.threeDays =
        window.localStorage.getItem(
            token + '|' + environment + '-elusive-target-3'
        ) === '1'
    notifications.oneDay =
        window.localStorage.getItem(
            token + '|' + environment + '-elusive-target-1'
        ) === '1'
    notifications.ended =
        window.localStorage.getItem(
            token + '|' + environment + '-elusive-target-ended'
        ) === '1'

    previousNotificationsState.almostPlayable = notifications.almostPlayable
    previousNotificationsState.becomesPlayable = notifications.becomesPlayable
    previousNotificationsState.sevenDays = notifications.sevenDays
    previousNotificationsState.fiveDays = notifications.fiveDays
    previousNotificationsState.threeDays = notifications.threeDays
    previousNotificationsState.oneDay = notifications.oneDay
    previousNotificationsState.ended = notifications.ended
}
</script>
<style lang="scss" scoped>
.site-header {
    margin: 0 20px;

    h1 {
        margin-top: 20px;
    }

    img {
        max-height: 100px;
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
            margin-bottom: 1rem;

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
</style>
