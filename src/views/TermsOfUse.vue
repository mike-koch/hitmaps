<template>
    <div
        class="content"
        style="background: url('/img/webp/backgrounds/loading.webp') no-repeat center center fixed; background-size: cover"
    >
        <div class="inner-content">
            <h1>Terms of Use</h1>
            <hr>
            <h3>Terminology</h3>
            <p>
                "HITMAPS" ("we", "site", "the site", "the service",
                "hitmaps.com") refers to the HITMAPS website. These terms
                are to be bound at all times and are available on the HITMAPS
                website. The terms of this agreement shall be active one day after
                last updated (shown below) and set forth by the HITMAPS
                administration.
            </p>
            <h3>Usage</h3>
            <p>
                By using any service or product from or visiting the website of
                HITMAPS, you automatically agree to the following terms. You
                must also explicitly agree to these terms when creating an account
                on the site. It is your responsibility to familiarize yourself with
                any modifications made to this agreement. Your continued use of this
                website will constitute acknowledgement of the modified terms of
                use.
            </p>
            <h3>Terms</h3>
            <h4>General</h4>
            <ul>
                <li>
                    All servers that contain / run site data are hosted off-site. We
                    are not responsible for any data loss or service interruption on
                    these servers. If the service level between one of these
                    companies is less than desired/expected/required, we may contact
                    the company requesting appropriate compensation and/or
                    explanation. We are not responsible for any data loss, security
                    breaches, privacy exploits, or any other incidents that may
                    unexpectedly occur; however we will contact you within seven (7)
                    business days should any breach regarding your personal data
                    occur.
                </li>
                <li>
                    We reserve the right to modify these terms at any time without
                    notice.
                </li>
                <li>
                    Disclaimer of Warranties and Limitations of Liability: This
                    website is provided on an "as is" and "as available" basis. Use
                    of this website is at your own risk. We and our suppliers
                    disclaim all warranties. Neither we nor our suppliers shall be
                    liable for any damages of any kind with the use of this website.
                </li>
                <li>
                    Governing Law: This site (excluding any linked sites) is
                    controlled by us from our offices within Ohio, United States of
                    America. It can be accessed from all 50 states, as well as from
                    other countries around the world. As each of these places has
                    laws that may differ from those of Ohio, by accessing this site
                    both of us agree that the statutes and laws of the State of
                    Ohio, without regard to the conflicts of laws principles thereof
                    and the United Nations Convention on the International Sales of
                    Goods, will apply to all matters relating to the use of this
                    site and the purchase of products and services available through
                    this site. Each of us agrees and hereby submits to the exclusive
                    personal jurisdiction and venue any court of competent
                    jurisdiction within the State of Ohio with respect to such
                    matters.
                </li>
            </ul>
            <h4>User-Submitted Data</h4>
            <ul>
                <li>
                    The site allows users from the public to submit content which
                    can be viewed in a public setting. All user-submitted content is
                    immediately available for viewing when created; no content is
                    manually moderated when initially created.
                </li>
                <li>
                    The site is not responsible for any content posted on the site.
                    However, we do actively moderate the system and remove any
                    offending / illegal / off-topic content.
                </li>
                <li>
                    Contributions containing, but not limited to, the following are
                    <b>strictly prohibited</b>:
                    <ul>
                        <li>
                            Information containing profanity (unless copied directly
                            from an authorized source)
                        </li>
                        <li>Information that is political in nature</li>
                        <li>
                            Information that contains illegal information
                            (including, but not limited to, piracy, warez, theft,
                            illegal drugs, etc (unless copied directly from an
                            authorized source))
                        </li>
                        <li>
                            Information that is not related to HITMAN&trade;,
                            Io-Interactive, or the site in general
                        </li>
                        <li>
                            Information that is malicious / potentially malicious in
                            nature
                        </li>
                    </ul>
                    <p>
                        Note: "Illegal" content is determined within the local,
                        state, and federal jurisdictions of the site. Any content
                        that violates these rules will be removed without warning.
                        The HITMAPS administration has the final say in all
                        contributions and whether a contribution violates any of these
                        rules.
                    </p>
                </li>
                <li>
                    In the event of any illegal or potentially illegal content
                    posted on the website (including, but not limited to, piracy,
                    threats of violence, etc), we will notify the proper law
                    enforcement agencies.
                </li>
                <li>
                    In the event of any user violating these terms, the user's
                    account and/or IP address may be banned, prohibiting them from
                    visiting the website in the future. We reserve the right to
                    enforce any action as we see fit.
                </li>
            </ul>
            <p>
                <b
                ><i
                >These terms are subject to change at any time without
                    notice. The current terms of use will be available on the
                    HITMAPS website. Changes to these terms must be
                    approved by the site administration.</i
                ></b
                >
            </p>

            <div><br />Last Updated on 2019-11-07</div>
            <p>Prior Versions:</p>
            <ul>
                <li><a href="https://gitlab.com/mike-koch/hitman-maps/raw/fb501f0270bf2d9b31f575057bb45db54d79ce42/src/views/TermsOfUse.vue">2019-07-29 to 2019-11-07</a></li>
                <li><a href="https://gitlab.com/mike-koch/hitman-maps/raw/0866c005b0a73e452eadc05cbda57ffc993614eb/src/views/TermsOfUse.vue">2018-12-09 to 2019-07-29</a></li>
            </ul>
        </div>
    </div>
</template>
<style lang="scss" scoped>
.content {
    > .inner-content {
        margin: 20px;
        background-color: white;
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

        li {
            font-family: 'Roboto', 'Arial', sans-serif;
            font-weight: lighter;
        }
    }
}
</style>

<script>
export default {
    name: 'terms-of-use',
    title: 'Terms of Use',
}
</script>
