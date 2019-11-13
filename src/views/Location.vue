<template>
    <div
        v-webp
        class="content"
        style="background: url('/img/jpg/backgrounds/loading.jpg') no-repeat center center fixed; background-size: cover"
    >
        <div class="site-container" v-if="game != null">
            <script type="application/ld+json">
                {
                    "@context": "https://schema.org",
                    "@type": "BreadcrumbList",
                    "itemListElement": [
                        {
                            "@type": "ListItem",
                            "position": 1,
                            "name": "Home",
                            "item": "{{ $domain }}"
                        },
                        {
                            "@type": "ListItem",
                            "position": 2,
                            "name": "{{ game.fullName }}",
                            "item": "{{ $domain }}/{{ game.slug }}"
                        }
                    ]
                }
            </script>
            <div class="text-center logo">
                <img
                    v-webp
                    :src="'/img/png/logos/' + game.slug + '.png'"
                    class="img-fluid"
                />
                <h2>{{ game.tagline }}</h2>
            </div>
            <div class="row loading" v-if="locations.length === 0">
                <div class="loader">
                    <loader></loader>
                </div>
            </div>
            <nav
                v-webp
                id="scrollspy"
                class="navbar locations"
                style="background: url('/img/jpg/backgrounds/loading.jpg') no-repeat center center fixed; background-size: cover"
            >
                <ul class="nav">
                    <li v-if="locations.length > 0" class="nav-item">
                        <router-link
                            :to="{ name: 'home' }"
                            class="nav-link"
                            data-target="#"
                        >
                            <img
                                src="/img/game-icons/campaign-transparent.png"
                                class="normal"
                                :alt="$t('home-icon')"
                            />
                            <img
                                src="/img/game-icons/campaign-inverted.png"
                                class="inverted"
                                :alt="$t('home-icon')"
                            />
                            Home
                        </router-link>
                    </li>
                    <li
                        v-for="location in locations"
                        :key="location.id"
                        class="nav-item"
                    >
                        <a
                            class="nav-link"
                            :href="'#' + location.slug"
                            :data-target="'#' + location.slug"
                        >
                            <img
                                src="/img/game-icons/location-transparent.png"
                                class="normal"
                                :alt="$t('location-icon')"
                            />
                            <img
                                src="/img/game-icons/location.png"
                                class="inverted"
                                :alt="$t('location-icon')"
                            />
                            {{ lang('locations.destinations.' + location.slug, location.destination) }}
                        </a>
                    </li>
                </ul>
            </nav>
            <div
                v-for="location in locations"
                :key="location.id"
                class="location"
                v-webp
                v-bind:style="{
                    background:
                        'url(/img/jpg/backgrounds/' +
                        game.slug +
                        '/' +
                        location.slug +
                        '.jpg) no-repeat center center fixed',
                    backgroundSize: 'cover'
                }"
            >
                <div class="header">
                    <a class="anchor" :id="location.slug"></a>
                    <h2>
                        <img
                            src="/img/game-icons/location.png"
                            class="img-fluid"
                            :alt="$t('location-icon')"
                        />
                        <p>
                            <span class="name">{{ lang('locations.destinations.' + location.slug, location.destination) }}</span>
                            <br />
                            <span class="country">
                                {{ lang('locations.countries.' + location.slug, location.destinationCountry) }}
                            </span>
                        </p>
                    </h2>
                </div>
                <div class="missions">
                    <div
                        class="row"
                        style="margin-left: 15px; margin-right: 15px;"
                    >
                        <div
                            v-for="mission in location.missions"
                            :key="mission.id"
                            class="col-xl-3 col-lg-4 col-md-6 col-sm-12"
                        >
                            <router-link
                                @click.native="saveMissionData(mission)"
                                :to="{
                                    name: 'map-view',
                                    params: {
                                        slug: location.game,
                                        location: location.slug,
                                        mission: mission.slug,
                                        difficulty: mission.difficulties[0].toLowerCase()
                                    }
                                }"
                                v-if="mission.difficulties.length == 1"
                            >
                                <div class="card mission">
                                    <div style="position: relative">
                                        <img
                                            v-if="
                                                mission.missionType !==
                                                    'Elusive Target'
                                            "
                                            v-webp
                                            :src="
                                                '/img/' + generateUrl(mission)
                                            "
                                            class="card-img-top"
                                            :alt="$t('mission-image')"
                                        />
                                        <img
                                            v-else
                                            :src="
                                                '/img/' + generateUrl(mission)
                                            "
                                            class="card-img-top"
                                            :alt="$t('mission-image')"
                                        />
                                        <div
                                            class="card-img-overlay d-flex flex-column justify-content-end"
                                            style="padding: 0"
                                        ></div>
                                    </div>
                                    <div class="card-footer">
                                        <div class="image">
                                            <img
                                                src="/img/game-icons/mission-inverted.png"
                                                class="normal img-fluid"
                                                :alt="$t('mission-icon')"
                                            />
                                            <img
                                                src="/img/game-icons/mission.png"
                                                class="inverted img-fluid"
                                                :alt="$t('mission-icon')"
                                            />
                                        </div>
                                        <div class="text">
                                            <h2>{{ lang('mission-types.' + mission.missionType.toLowerCase(), mission.missionType) }}</h2>
                                            <h1>{{ mission.missionType !== 'Elusive Target' ? lang('missions.' + mission.slug, mission.name) : mission.name }}</h1>
                                        </div>
                                    </div>
                                </div>
                            </router-link>
                            <div v-else class="card mission">
                                <div style="position: relative">
                                    <img
                                        v-webp
                                        :src="'/img/' + generateUrl(mission)"
                                        class="card-img-top"
                                        alt=""
                                    />
                                    <div
                                        class="card-img-overlay d-flex flex-column justify-content-end"
                                        style="padding: 0"
                                    >
                                        <div class="row difficulties">
                                            <router-link
                                                @click.native="
                                                    saveMissionData(mission)
                                                "
                                                :to="{
                                                    name: 'map-view',
                                                    params: {
                                                        slug: location.game,
                                                        location: location.slug,
                                                        mission: mission.slug,
                                                        difficulty: difficulty.toLowerCase()
                                                    }
                                                }"
                                                v-for="difficulty in mission.difficulties"
                                                :key="difficulty"
                                                class="col"
                                            >
                                                <div
                                                    style="padding-top: 10px; padding-bottom: 10px"
                                                >
                                                    <img
                                                        :src="
                                                            '/img/game-icons/' +
                                                                difficulty.toLowerCase() +
                                                                '.png'
                                                        "
                                                        class="normal"
                                                    />
                                                    <img
                                                        :src="
                                                            '/img/game-icons/' +
                                                                difficulty.toLowerCase() +
                                                                '-inverted.png'
                                                        "
                                                        class="inverted"
                                                    />
                                                    {{ lang('difficulties.' + difficulty, difficulty) }}
                                                </div>
                                            </router-link>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="image">
                                        <img
                                            src="/img/game-icons/mission-inverted.png"
                                            class="normal img-fluid"
                                            :alt="$t('mission-icon')"
                                        />
                                        <img
                                            src="/img/game-icons/mission.png"
                                            class="inverted img-fluid"
                                            :alt="$t('mission-icon')"
                                        />
                                    </div>
                                    <div class="text">
                                        <h2>{{ lang('mission-types.' + mission.missionType.toLowerCase(), mission.missionType) }}</h2>
                                        <h1>{{ lang('missions.' + mission.slug, mission.name) }}</h1>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Loader from '../components/Loader.vue'

export default {
    name: 'level-select',
    components: {
        Loader
    },
    computed: {
        game: function() {
            return this.$store.state.game
        }
    },
    title() {
        return this.$store.state.game !== null
            ? this.$store.state.game.fullName
            : 'Loading'
    },
    data() {
        return {
            locations: []
        }
    },
    methods: {
        generateUrl: function(mission) {
            if (mission.missionType !== 'Elusive Target') {
                return (
                    'png/mission-thumbnails/' +
                    this.game.slug +
                    '/' +
                    mission.slug +
                    '.png'
                )
            }
            if (this.game != null && this.game.slug == 'hitman') {
                return 'jpg/elusive-targets/legacy/' + mission.slug + '.jpg'
            }

            return 'jpg/elusive-targets/' + mission.slug + '.jpg'
        },
        saveMissionData: function(mission) {
            if (
                this.$store.state.mission != null &&
                this.$store.state.mission.slug !== mission.slug
            )
                this.$store.commit('SET_MISSION', mission)
        }
    },
    created: function() {
        if (this.game === null || this.game.slug !== this.$route.params.slug)
            this.$store.dispatch('loadGame', this.$route.params.slug)
        this.$http
            .get(
                this.$domain +
                    '/api/v1/games/' +
                    this.$route.params.slug +
                    '/locations'
            )
            .then(resp => (this.locations = resp.data))
    }
}
</script>

<style lang="scss" scoped>
.content {
    > a {
        text-decoration: none;
        display: block;
        margin: 20px;
    }
    .logo {
        margin-top: 20px;
    }

    /* Locations page */
    .site-container {
        padding-right: 50px;
        padding-left: 50px;
        margin-right: auto;
        margin-left: auto;

        .loader {
            margin: 100px auto 0;
        }
    }

    .location {
        margin-left: -50px;
        margin-right: -50px;
        padding-top: 20px;
        padding-bottom: 20px;

        .anchor {
            display: block;

            @media (min-width: 992px) {
                position: relative;
                top: -70px;
                visibility: hidden;
            }
        }

        .header {
            margin-left: 30px;
            text-shadow: 2px 2px 2px #000;

            h2 {
                font-size: 1.5rem;

                img {
                    display: inline-block;
                    vertical-align: text-bottom;
                    max-height: 63px;
                    max-width: 63px;
                    filter: drop-shadow(2px 2px 2px #000);
                    margin-right: 7px;
                }

                p {
                    display: inline-block;
                    text-align: left;

                    .name {
                        font-size: 2rem;
                    }
                }
            }
        }
    }
    .navbar.locations {
        z-index: 1030;
        background: gray;
        text-transform: uppercase;
        text-shadow: 2px 2px 2px #000;
        margin-left: -50px;
        margin-right: -50px;
        padding: 0;

        @media (min-width: 992px) {
            position: sticky;
            top: 0;
        }

        a {
            color: white;
            padding: 20px;

            img {
                height: 30px;
                width: 30px;

                &.normal {
                    display: inline-block;
                    filter: drop-shadow(2px 2px 2px #000);
                }

                &.inverted {
                    display: none;
                    filter: none;
                }
            }

            &:hover,
            &:active,
            &.active {
                background: #ff003c;

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

    .missions {
        .card {
            border: none;
            border-radius: 0;

            .card-img-top {
                border-radius: 0;
            }
        }

        header {
            text-shadow: -1px -1px 0 #000, 1px -1px 0 #000, -1px 1px 0 #000,
                1px 1px 0 #000;
        }

        .mission {
            margin-bottom: 10px;

            .difficulties {
                margin: 0;
                background-color: rgba(0, 0, 0, 0.2);

                a {
                    color: white;
                    text-decoration: none;
                    opacity: 0.85;

                    &:hover {
                        background-color: #fc003b;
                        opacity: 1;

                        img {
                            &.normal {
                                display: none;
                            }

                            &.inverted {
                                display: inline-block;
                            }
                        }
                    }

                    img {
                        height: 48px;
                        width: 48px;

                        &.normal {
                            display: inline-block;
                        }

                        &.inverted {
                            display: none;
                        }
                    }
                }
            }

            .card-footer {
                padding: 15px;
                background: white;
                color: #000;
                text-shadow: none;
                border-radius: 0;

                .image {
                    display: inline-block;
                    vertical-align: top;
                    margin-right: 5px;

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

                    h2 {
                        font-size: 16px;
                        margin-bottom: 0;
                        color: #fc003b;
                        font-weight: 500;
                    }

                    h1 {
                        font-size: 21px;
                        margin-bottom: 0;
                        text-transform: uppercase;
                    }
                }
            }

            .name {
                border: solid 1px #fff;
                color: #fff;
                display: flex;
                flex-direction: column;

                > p {
                    flex-grow: 1;
                }

                img {
                    width: 32px;
                    height: 32px;
                    margin-top: 0;
                    margin-bottom: 0;
                }

                margin-right: 5px;
            }

            @media (min-width: 992px) {
                .difficulty {
                    text-align: center;
                    background: #fff;
                    padding-left: 0;
                    padding-right: 0;
                    margin-right: 5px;

                    > a {
                        display: flex;
                        flex-direction: column;
                        width: 100%;
                        height: 100%;
                        padding-left: 15px;
                        padding-right: 15px;
                        color: #000;
                        font-size: 1.5rem;

                        img {
                            margin: 20px auto;
                        }

                        img.normal {
                            display: block;
                            max-height: 100px;
                            max-width: 100px;
                        }

                        img.inverted {
                            display: none;
                            max-height: 100px;
                            max-width: 100px;
                        }

                        &:hover {
                            background: #ff003c;
                            text-decoration: none;
                            color: #fff;

                            img.normal {
                                display: none;
                            }

                            img.inverted {
                                display: block;
                            }
                        }
                    }
                }
            }

            @media (max-width: 991px) {
                .name {
                    margin-right: 0;
                }

                .difficulty {
                    &:nth-child(odd) {
                        background: #fff;
                    }

                    &:nth-child(even) {
                        background: #eee;
                    }

                    > a {
                        img.normal {
                            display: inline-block;
                            max-height: 32px;
                            max-width: 32px;
                        }

                        img.inverted {
                            display: none;
                            max-height: 32px;
                            max-width: 32px;
                        }

                        &:hover {
                            text-decoration: none;
                            color: #000;
                        }

                        p {
                            display: inline-block;
                        }
                    }
                }
            }
        }

        a:hover .card-footer {
            color: white;
            background-color: #fc003b;

            .image img {
                &.normal {
                    display: none;
                }

                &.inverted {
                    display: block;
                }
            }

            .text {
                h2 {
                    color: white;
                }
            }
        }
    }

    h2 {
        text-transform: uppercase;
        color: #fff;
        margin-bottom: 20px;
    }
}
</style>
