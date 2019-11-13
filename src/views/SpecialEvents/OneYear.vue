<template>
    <div class="content">
        <header>
            <div class="row">
                <div class="col text-center site-header">
                    <h1>{{ $t("interactive-maps-for") }}</h1>
                    <img
                            v-webp
                            src="/img/png/logos/hitman2.png"
                            class="img-fluid"
                    />
                    <h2>One Year Later</h2>
                </div>
            </div>
        </header>
        <section class="intro">
            <div class="inner-content">
                <h3>Introduction</h3>
                <p>On November 20, 2018, HITMAN™️ 2 Maps was released. The site was basic back then, with barren maps
                    and
                    no helpful information available. A year later, and the site today looks nothing like it did then.
                    Let's
                    take a look at the history of HITMAN™️ 2 Maps, and a look ahead at its future.</p>
            </div>
        </section>
        <section class="timeline">
            <div class="inner-content">
                <h3>Timeline</h3>
                <div id="timeline-content">
                    <ul class="timeline">
                        <li class="event">
                            <h4>September 28, 2018</h4>
                            <h3>Development Begins</h3>
                            <p>Development begins on HITMAN™️ 2 Maps with an <a href="https://gitlab.com/mike-koch/hitman-maps/commit/06ff5ccdba274aa6871eeadb510487815539891c" target="_blank">initial README.md commit</a>.</p>
                        </li>
                        <li class="event">
                            <h4>November 14, 2018</h4>
                            <h3>Initial Discovery</h3>
                            <p>Notex on the HITMAN™️ Forums discovers HITMAN™️ 2 Maps mid-development.</p>
                        </li>
                        <li class="event">
                            <h4>November 16, 2018</h4>
                            <h3>HITMAN™️ Forum Introduction</h3>
                            <p>MrMike (creator of HITMAN™️ 2 Maps) introduces himself, to show that development is on-going</p>
                        </li>
                        <li class="event">
                            <h4>November 20, 2018</h4>
                            <h3>Initial Release</h3>
                            <p>HITMAN™️ 2 Maps is officially released, calling for volunteers to help map the site</p>
                            <a href="/img/jpg/one-year/og-site.png" target="_blank">
                                <img class="img-fluid" src="/img/jpg/one-year/og-site.png" alt="Screenshot of hitmaps.com on initial release">
                            </a>
                            <p class="image-caption">Screenshot of HITMAN™️ 2 Maps on initial release</p>
                        </li>
                        <li class="event">
                            <h4>December 30, 2018</h4>
                            <h3>Main Mission Maps Complete</h3>
                            <p>Items for the main mission maps are completed, with the exception of disguise areas</p>
                        </li>
                        <li class="event">
                            <h4>February 16, 2019</h4>
                            <h3>Elusive Target Push Notifications</h3>
                            <p>Services are created to keep track of Elusive Targets via IO Interactive's public API, providing push notifications for public use.</p>
                        </li>
                        <li class="event">
                            <h4>April 1-2, 2019</h4>
                            <h3>Legacy Game Support 😉</h3>
                            <p>For a couple days, HITMAN™️ 2 Maps supported Hitman: Codename 47 and Hitman 2: Silent Assassin. Clippy also joined in to assist users as they navigated around the site.</p>
                            <a href="/img/jpg/one-year/april-fools.png" target="_blank">
                                <img class="img-fluid" src="/img/jpg/one-year/april-fools.png" alt="Screenshot of hitmaps.com on April 2, courtesy of archive.org">
                            </a>
                            <p class="image-caption">Screenshot of HITMAN™️ 2 Maps on April 2. Source: archive.org</p>
                        </li>
                        <li class="event">
                            <h4>April 26, 2019</h4>
                            <h3>Sniper Assassin</h3>
                            <p>Sniper Assassin maps are added for all locations.</p>
                            <a href="/img/jpg/one-year/sniper-assassin.png" target="_blank">
                                <img src="/img/jpg/one-year/sniper-assassin.png" alt="Sniper Assassin tile" class="img-fluid">
                            </a>
                        </li>
                        <li class="event">
                            <h4>September 7, 2019</h4>
                            <h3>Hallo!</h3>
                            <p>HITMAN™️ 2 Maps is translated into 11 different languages, maintained by the HITMAN™️ community.</p>
                        </li>
                    </ul>
                </div>
            </div>
        </section>
        <section class="by-the-numbers">
            <div class="inner-content">
                <h3>Some Statistics</h3>
                <div class="row">
                    <div class="col">
                        <p class="stat-number">11,998</p>
                        <p>Items</p>
                    </div>
                    <div class="col">
                        <p class="stat-number">40</p>
                        <p>Site Editors</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <p class="stat-number">211</p>
                        <p>Discord Members</p>
                    </div>
                    <div class="col">
                        <p class="stat-number">Chasing a Ghost</p>
                        <p>Map With Most Items - 1,158 Items</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <p class="stat-number">Freeform Training</p>
                        <p>Map With Least Items - 90 Items</p>
                    </div>
                    <div class="col">
                        <p class="stat-number">The Ark Society</p>
                        <p>Most Popular Map - 17,454 Pageviews</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <p class="stat-number">Crime and Punishment</p>
                        <p>Least Popular Map - 248 Pageviews</p>
                    </div>
                    <div class="col">
                        <p class="stat-number">268</p>
                        <p>Areas of Foliage</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <p class="stat-number">6,518</p>
                        <p>Disguise Trespassing / Hostile Regions</p>
                    </div>
                    <div class="col">
                        <p class="stat-number">269</p>
                        <p>Disguises</p>
                    </div>
                </div>
                <p><i>As of November 2, 2019. Discord members does not include bot accounts.</i></p>
            </div>
        </section>
        <section class="the-future">
            <div class="inner-content">
                <h3>The Future</h3>
                <p>The future of HITMAN™️ 2 Maps is looking quite strong. Here are planned throughout the rest of 2019 and 2020:</p>
                <section class="legacy-maps">
                    <h4>Ghost Mode Maps</h4>
                    <p>Ghost Mode itmes are going to be having their own dedicated home on HITMAN™️ 2 Maps. There are some Ghost Mode
                    items already added; however they will have their own home in the near future.</p>
                    <h4>Legacy Maps</h4>
                    <p>There are tentative plans to include maps for legacy locations, such as Hitman: Blood Money and
                    Hitman: Contracts.  We're currently unsure about supporting Hitman: Absolution as there is no
                    in-game map.</p>
                </section>
                <section class="hold-up">
                    <p>Hold up, but this is HITMAN™️ 2 Maps... why would you include legacy games?</p>
                    <br>
                    <p>Well...</p>
                </section>
                <section class="hitmaps text-center">
                    <img class="img-fluid" src="/img/jpg/one-year/hitmaps-logo.png" style="height: 100px; margin-bottom: 10px;">
                    <h4>HITMAN™️ 2 Maps is becoming HITMAPS!</h4>
                    <p>We've acquired the hitmaps.com domain, allowing us to provide maps across more Hitman games than
                    just HITMAN™️ and HITMAN™️ 2. Of course, we plan on continuing with HITMAN™️ 3 (or whatever the next
                    game is called), on top of the other games as described earlier. Over the next week, the social media
                    channels and domain names will switch over to the HITMAPS name and artwork. Oh, and we also
                    registered hitman3maps.com, just in case 😉.</p>
                    <p>Needless to say, this site is in it for the long haul. We're not going away anytime soon.</p>
                </section>
            </div>
        </section>
        <section class="special-thanks">
            <div class="inner-content">
                <h3>Special Thanks</h3>
                <p>Without the help of the community, this site would not be where it is today.  I'd like to
                thank the following for their help and what they do:</p>
                <div class="row">
                    <div class="col">
                        <img v-webp src="/img/png/logos/ioi.png">
                        <p style="font-weight: bolder">IO Interactive</p>
                        <p>Well, it goes without saying this, but without IO Interactive, there wouldn't be HITMAN™️.
                        Without HITMAN™️, there wouldn't be HITMAN™️ 2 Maps.</p>
                        <p style="font-size: .5rem">(Oh, and thanks for making your game files decently easy to access.
                        It made for making the map images <i>really</i> easy ;) )</p>
                    </div>
                    <div class="col">
                        <span class="fa-stack fa-2x" style="font-size: 6.2rem">
                            <i class="fas fa-square fa-stack-2x" style="color: #7289DA"></i>
                            <i class="fab fa-discord fa-stack-1x fa-inverse"></i>
                        </span>
                        <p style="font-weight: bolder">HITMAN™️ 2 Maps Discord Members</p>
                        <p>The members of the HITMAN™️ 2 Maps Discord, many of them who are map editors (but not all),
                        have given me a group of people to converse with on a daily basis. We've talked about random,
                        off-topic nonsense to also deciding what features should be added to the site next.</p>
                    </div>
                    <div class="col">
                        <img src="/img/jpg/one-year/hitmanforum.png">
                        <p style="font-weight: bolder">Hitman Forum</p>
                        <p>The Hitman Forum is the first place I posted about HITMAN™️ 2 Maps' creation. I honestly did
                        not know what to expect when asking for help mapping (actually, I was worried that no one would
                        want to help at all); however I was pleasantly surprised to find out that scores of people were
                        willing to help map their favorite map(s). This site would still be a work in progress if it was
                        not for them.</p>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>

<script>
    export default {
        name: 'one-year',
        title: 'HITMAN™️ 2 Maps\' One Year Anniversary'
    }
</script>

<style lang="scss" scoped>
    .content {
        background: url('/img/jpg/one-year/miami.jpg') no-repeat center center fixed;
        background-size: cover;

        header {
            background: rgba(0, 0, 0, .4);
            margin-top: 0;
            margin-right: 0;

            h2 {
                text-transform: uppercase;
            }
        }

        .intro,
        .by-the-numbers,
        .special-thanks {
            background: #ff003c;
            color: #fff;
        }

        .by-the-numbers,
        .special-thanks {
            .row {
                text-align: center;

                .stat-number {
                    font-size: 2rem;
                }

                & + .row {
                    margin-top: 20px;
                }

                img {
                    height: 200px;
                }
            }
        }

        .the-future {
            background: url('/img/jpg/one-year/hitmaps-background.jpg') no-repeat center center fixed;

            > div {
                background: rgba(0, 0, 0, .6);
                color: white;
            }

            .hold-up {
                margin: 100px -50px;
                padding: 100px;
                font-size: 2em;
                text-align: center;
                background: black;
            }
        }

        section.timeline {
            .image-caption {
                text-align: center;
            }

            background: #c41e3d;
            color: #dbd4d3;

            /* region timeline */
            @import url('https://fonts.googleapis.com/css?family=Chivo:300,300i,400,400i,700,700i,900,900i|Saira+Extra+Condensed:100,200,300,400,500,600,700,800|Saira:100,200,300,400,500,600,700,800');

            /* Variables */

            $background: #252827;

            $color-primary: #dbd4d3;
            $color-light: white;
            $color-dim: #6c6d6d;
            $spacing: 50px;
            $radius: 4px;

            $date: 120px;
            $dotborder: 4px;
            $dot: 11px;
            $line: 4px;

            $font-title: 'Saira', sans-serif;
            $font-text: 'Chivo', sans-serif;

            /* Base */

            body {
                background: $background;
                font-size: 16px;
            }

            p {
                font-weight: 300;
            }

            a {
                color: #fff;
                text-decoration: none;
            }

            a:hover {
                color: #dbd4d3;
            }

            strong {
                font-weight: 600;
            }

            h1 {
                font-family: $font-title;
                letter-spacing: 1.5px;
                color: $color-light;
                font-weight: 400;
                font-size: 2.4em;
            }

            #timeline-content {
                margin-top: $spacing;
                text-align: center;
            }

            /* Timeline */

            ul.timeline {
                border-left: $line solid $color-primary;
                border-bottom-right-radius: $radius;
                border-top-right-radius: $radius;
                background: fade($color-light, 3%);
                color: fade(white, 80%);
                font-family: $font-text;
                margin: $spacing auto;
                letter-spacing: 0.5px;
                position: relative;
                line-height: 1.4em;
                font-size: 1.03em;
                padding: $spacing;
                list-style: none;
                text-align: left;
                font-weight: 100;
                max-width: 60%;

                h1 {
                    font-family: $font-title;
                    letter-spacing: 1.5px;
                    font-weight: 100;
                    font-size: 1.4em;
                }

                h2, h3 {
                    font-family: $font-title;
                    letter-spacing: 1.5px;
                    font-weight: 400;
                    font-size: 1.4em;
                }

                h4 {
                    font-size: .9em;
                    font-weight: 100;
                }

                .event {
                    border-bottom: 1px dashed fade($color-light, 10%);
                    padding-bottom: ($spacing * 0.5);
                    margin-bottom: $spacing;
                    position: relative;

                    &:last-of-type {
                        padding-bottom: 0;
                        margin-bottom: 0;
                        border: none;
                    }

                    &:before, &:after {
                        position: absolute;
                        display: block;
                        top: 0;
                    }

                    &:before {
                        left: ((($date * 0.6) + $spacing + $line + $dot + ($dotborder * 2)) * 1.5) * -1;
                        color: fade($color-light, 40%);
                        content: attr(data-date);
                        text-align: right;
                        font-weight: 100;
                        font-size: 0.9em;
                        min-width: $date;
                        font-family: $font-title;
                    }

                    &:after {
                        box-shadow: 0 0 0 $dotborder fade($color-primary,100%);
                        left: ($spacing + $line + ($dot * 0.35)) * -1;
                        background: lighten($background,5%);
                        border-radius: 50%;
                        height: $dot;
                        width: $dot;
                        content: "";
                        top: 5px;
                    }
                }
            }
            /* endregion */
        }

        /*
        Other colors; 3C0919, 7D1128
         */

        section {
            h3 {
                font-size: 2.5rem;
                text-transform: uppercase;
                margin-bottom: 20px;
            }

            > .inner-content {
                padding: 30px 50px 100px 50px;
                font-family: 'Lato', sans-serif;

                h3 {
                    font-weight: 700;
                    font-family: 'PT Serif', serif;
                }
            }
        }


    }
</style>
