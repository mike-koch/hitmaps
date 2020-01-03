<template>
    <div
        class="content"
        style="background: url('https://media.hitmaps.com/img/backgrounds/menu_bg.jpg') no-repeat center center fixed; background-size: cover"
    >
        <div class="inner-content">
            <h1>Support the Site</h1>
            <h2>Ways to Say "Thanks!"</h2>
            <hr>
            <h3>Patreon</h3>
            <p>
                I have recently created a Patreon page for all of my works (not just HITMAPS™️). By becoming a patron,
                you will receive certain perks. Becoming a patron will result in a monthly contribution. Simply click
                the button below to become a patron.
            </p>
            <a href="https://www.patreon.com/mike_koch" class="btn patreon-button">
                <i class="fab fa-patreon"></i>
                Become a Patron
            </a>
            <h3 style="margin-top: 20px;">PayPal</h3>
            <p>
                I currently accept tips/contributions via PayPal. Simply click the button below to make a contribution.
                Unless otherwise stated, these contributions are not tax-deductible in any way. You will be credited
                below with your donation.
            </p>
            <!-- region PayPal Button -->
            <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
                <input type="hidden" name="cmd" value="_s-xclick" />
                <input type="hidden" name="hosted_button_id" value="SE5ALRGBQCCUW" />
                <button type="submit" class="btn paypal-button">
                    <i class="fab fa-paypal"></i>
                    Send Contribution via PayPal
                </button>
                <img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1" />
            </form>
            <!-- endregion -->
        </div>
        <div class="home-button">
            <router-link :to="{ name: 'home' }">
                <game-button>
                    <img src="/img/game-icons/campaign.png"
                         class="normal img-fluid"
                         alt="Return Home" />
                    <img src="/img/game-icons/campaign-inverted.png"
                         class="inverted img-fluid"
                         alt="Return Home"
                    />
                    Home
                </game-button>
            </router-link>
        </div>
    </div>
</template>

<script>
import GameButton from "../components/GameButton";
export default {
    name: 'support-the-site',
    components: {GameButton},
    title: 'Support the Site',
}
</script>

<style lang="scss" scoped>
.content {
    > .home-button {
        margin: 20px;

        a:hover {
            text-decoration: none;
        }
    }

    > .inner-content {
        background-color: white;
        margin: 20px;
        padding: 20px;
        font-family: 'nimbus_sans_lbold', sans-serif;

        h1 {
            text-transform: uppercase;
        }

        h2 {
            font-size: 1.25rem;
        }

        p {
            font-family: 'Roboto', 'Arial', sans-serif;
            font-weight: lighter;
        }

        a {
            color: #000;

            &.patreon-button {
                border-radius: 0;
                background: #e7705c;
                color: #fff;

                &:hover {
                    background: #cd685c;
                }
            }
        }

        .paypal-button {
            border-radius: 0;
            background: #00457c;
            color: #fff;

            &:hover {
                background: #003060;
            }
        }
    }
}

#ppBody {
    font-size: 11pt;
    width: 100%;
    margin: 0 auto;
    text-align: justify;
}

#ppHeader {
    font-size: 21pt;
    width: 100%;
    margin: 0 auto;
}

.ppConsistencies {
    display: none;
}
</style>
