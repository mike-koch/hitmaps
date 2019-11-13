<template>
    <div
        class="content"
        style="background: url('/img/jpg/backgrounds/loading.jpg') no-repeat center center fixed; background-size: cover"
    >
        <header class="row">
            <div class="col text-center site-header">
                <router-link :to="{ name: 'home' }">
                    <img
                            v-webp
                            src="/img/png/logos/hitmaps.png"
                            class="img-fluid"
                    />
                </router-link>
                <h1>{{ $t('interactive-maps-for-hitman') }}</h1>
                <h2>{{ $t('authentication.login-or-create-an-account') }}</h2>
            </div>
        </header>
        <div class="row">
            <div class="col-md-6 offset-md-3 register">
                <alert
                    v-for="message in register.messages"
                    :type="message.type"
                >
                    {{ message.messageHtml }}
                </alert>
                <div class="login-card" v-if="!register.registrationComplete">
                    <h3>{{ $t('authentication.create-an-account') }}</h3>
                    <div class="form-group row">
                        <label for="name" class="col-md-3 col-form-label">
                            <i class="fas fa-user-plus"></i>
                            {{ $t('profile.name') }}
                        </label>
                        <div class="col-md-9">
                            <input
                                type="text"
                                class="form-control"
                                :class="{
                                    'is-invalid': errors.has('name'),
                                    'text-danger': errors.has('name')
                                }"
                                id="register-name"
                                placeholder="Carlton Smith"
                                name="name"
                                v-validate="'required'"
                                v-model="register.name"
                                required
                            />
                            <div class="form-text text-danger">
                                {{ errors.first('name') }}
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="email" class="col-md-3 col-form-label">
                            <i class="fas fa-envelope"></i>
                            {{ $t('authentication.email') }}
                        </label>
                        <div class="col-md-9">
                            <input
                                type="email"
                                class="form-control"
                                :class="{
                                    'is-invalid': errors.has('email'),
                                    'text-danger': errors.has('email')
                                }"
                                id="register-email"
                                placeholder="carlton.smith@example.com"
                                v-validate="'required|email'"
                                name="email"
                                data-parsley-remote="/user/register/verify-email"
                                data-parsley-remote-message="An account with this email already exists"
                                v-model="register.email"
                                required
                            />
                            <div class="form-text text-danger">
                                {{ errors.first('email') }}
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="password" class="col-md-3 col-form-label">
                            <i class="fas fa-key"></i>
                            {{ $t('authentication.password') }}
                        </label>
                        <div class="col-md-9">
                            <input
                                type="password"
                                class="form-control"
                                :class="{
                                    'is-invalid': errors.has('password'),
                                    'text-danger': errors.has('password')
                                }"
                                id="register-password"
                                placeholder="********"
                                v-validate="'required|min:8'"
                                name="password"
                                data-parsley-error-message="Password must be at least 8 characters"
                                v-model="register.password"
                                required
                            />
                            <div class="form-text text-danger">
                                {{ errors.first('password') }}
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label
                            for="confirm-password"
                            class="col-md-3 col-form-label"
                        >
                            <i class="fas fa-key"></i>
                            {{ $t('authentication.confirm-password') }}
                        </label>
                        <div class="col-md-9">
                            <input
                                type="password"
                                class="form-control"
                                :class="{
                                    'is-invalid': errors.has(
                                        'confirm-password'
                                    ),
                                    'text-danger': errors.has(
                                        'confirm-password'
                                    )
                                }"
                                id="register-confirm-password"
                                name="confirm-password"
                                placeholder="********"
                                v-validate="{ is: register.password }"
                                v-model="register.confirmPassword"
                                required
                            />
                            <div class="form-text text-danger">
                                {{ errors.first('confirm-password') }}
                            </div>
                        </div>
                    </div>
                    <div class="form-group row has-feedback">
                        <div class="col-md-9 offset-md-3">
                            <div class="checkbox">
                                <label>
                                    <input
                                        type="checkbox"
                                        name="terms"
                                        data-error="You must agree to the Terms of Use and Privacy Policy"
                                        v-validate="'required'"
                                        required
                                    />
                                    <i18n path="authentication.agree-to-terms">
                                        <span slot="termsOfUse">
                                            <a href="/terms-of-use" target="_blank">
                                                {{ $t('authentication.terms-of-use') }}
                                            </a>
                                        </span>
                                        <span slot="privacyPolicy">
                                            <a href="/privacy-policy" target="_blank">
                                                {{ $t('authentication.privacy-policy') }}
                                            </a>
                                        </span>
                                    </i18n>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row has-feedback">
                        <div class="col-md-9 offset-md-3">
                            <vue-recaptcha
                                @verify="recaptcha = $event"
                                @expired="recaptcha = ''"
                                :sitekey="recaptchaSiteKey"
                            ></vue-recaptcha>
                            <input
                                type="hidden"
                                class="form-control"
                                name="recaptcha"
                                v-validate
                                v-model="recaptcha"
                                data-vv-scope="login"
                                required
                            />
                            <span
                                v-show="errors.has('login.recaptcha')"
                                class="form-text text-danger"
                            >
                                {{ errors.first('login.recaptcha') }}
                            </span>
                        </div>
                    </div>
                </div>
                <div
                    class="sign-in-button"
                    v-if="!register.registrationComplete"
                >
                    <game-button
                        @click="doRegister"
                        :disabled="errors.items.length > 0"
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
                        {{ $t('authentication.register') }}
                    </game-button>
                </div>
                <div class="sign-in-button">
                    <router-link :to="{ name: 'login' }">
                        <game-button>
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
                            {{ $t('authentication.back-to-login') }}
                        </game-button>
                    </router-link>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import VeeValidate from 'vee-validate'
import VueRecaptcha from 'vue-recaptcha'
import Vue from 'vue'
import Alert from '../components/Alert'
import Modal from '../components/Modal'
import GameButton from '../components/GameButton'

Vue.use(VeeValidate)
VeeValidate.configure({
    events: 'change'
})
export default {
    name: 'register',
    data() {
        return {
            recaptchaSiteKey: process.env.VUE_APP_RECAPTCHA_KEY,
            register: {
                name: '',
                email: '',
                password: '',
                confirmPassword: '',
                messages: [],
                registrationComplete: false
            },
            recaptcha: ''
        }
    },
    components: {
        GameButton,
        Modal,
        Alert,
        VueRecaptcha
    },
    methods: {
        doRegister: function() {
            this.$validator.validate().then(result => {
                if (result) {
                    const data = new FormData()
                    data.append('name', this.register.name)
                    data.append('email', this.register.email)
                    data.append('password', this.register.password)
                    data.append(
                        'confirm-password',
                        this.register.confirmPassword
                    )
                    data.append('g-recaptcha-response', this.recaptcha)

                    this.$request(true, 'web/user/register', data).then(
                        resp => {
                            this.register.messages = resp.data.data.messages
                            this.register.registrationComplete = true
                        }
                    )
                }
            })
        },
        passwordsMatch: function() {
            return this.register.password === this.register.confirmPassword
        },
        validEmail: function(email) {
            var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
            return re.test(email)
        }
    },
    created: function() {
        this.$validator.localize({
            en: {
                messages: {
                    email: 'This value must be a valid email.',
                    required: 'This field is required.',
                    is: 'Passwords must match'
                },
                custom: {
                    recaptcha: {
                        required: 'Please complete the captcha.'
                    }
                }
            }
        })
    }
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

.row {
    .col-md-6.register {
        background: transparent;
        border: none;

        .alert {
            margin: 10px;
        }

        .login-card {
            margin: 10px;
            background: #fff;
            padding: 1rem;
        }

        h3 {
            font-family: 'nimbus_sans_lbold', sans-serif;
            text-transform: uppercase;
            border-bottom: none;
            border-radius: 0;
        }

        a:hover {
            text-decoration: none;
        }

        .sign-in-button {
            margin: 0 10px 10px;
            padding: 0;
        }
    }
}
</style>
