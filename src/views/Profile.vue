<template>
    <div
        class="content"
        style="background: url('https://media.hitmaps.com/img/backgrounds/menu_bg.jpg') no-repeat center center fixed; background-size: cover"
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
                <h2>{{ $t('profile.manage-profile') }}</h2>
            </div>
        </header>
        <div
                class="row loading"
                v-if="model.email === ''"
        >
            <div class="loader">
                <loader></loader>
            </div>
        </div>
        <div class="row" v-if="model.email !== ''">
            <div class="col login">
                <div class="login-card">
                    <h3>{{ $t('profile.change-name') }}</h3>
                    <div class="form-group row has-feedback">
                        <label for="email" class="col-form-label col-md-4">
                            <i class="fas fa-envelope"></i>
                            {{ $t('authentication.email') }}
                        </label>
                        <div class="col-md-8">
                            <input
                                type="email"
                                class="form-control"
                                name="email"
                                v-model="model.email"
                                disabled
                                required
                            />
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="form-group row has-feedback">
                        <label for="name" class="col-form-label col-md-4">
                            <i class="fas fa-user"></i>
                            {{ $t('profile.name') }}
                        </label>
                        <div class="col-md-8">
                            <input
                                type="text"
                                class="form-control"
                                name="name"
                                placeholder="Carlton Smith"
                                v-model="model.name"
                                required
                            />
                            <span
                                class="glyphicon form-control-feedback"
                                aria-hidden="true"
                            ></span>
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-5 offset-md-4">
                            <button
                                class="btn btn-primary"
                                type="submit"
                                @click="changeName"
                            >
                                <i class="fas fa-check"></i>
                                {{ $t('profile.save-name') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col login">
                <div class="login-card">
                    <h3>{{ $t('profile.change-password') }}</h3>
                    <div class="form-group row has-feedback">
                        <label
                            for="current-password"
                            class="col-form-label col-md-5"
                        >
                            <i class="fa fa-key"></i>
                            {{ $t('profile.current-password') }}
                        </label>
                        <div class="col-md-7">
                            <input
                                type="password"
                                class="form-control"
                                name="current-password"
                                id="current-password"
                                v-model="model.currentPassword"
                                placeholder="********"
                                required
                            />
                            <span
                                class="glyphicon form-control-feedback"
                                aria-hidden="true"
                            ></span>
                        </div>
                    </div>
                    <div class="form-group row has-feedback">
                        <label for="password" class="col-form-label col-md-5">
                            <i class="fa fa-key"></i>
                            {{ $t('profile.new-password') }}
                        </label>
                        <div class="col-md-7">
                            <input
                                type="password"
                                class="form-control"
                                name="password"
                                id="password"
                                placeholder="********"
                                v-model="model.newPassword"
                                minlength="8"
                                required
                            />
                            <span
                                class="glyphicon form-control-feedback"
                                aria-hidden="true"
                            ></span>
                        </div>
                    </div>
                    <div class="form-group row has-feedback">
                        <label
                            for="confirm-password"
                            class="col-form-label col-md-5"
                        >
                            <i class="fa fa-key"></i>
                            {{ $t('profile.confirm-new-password') }}
                        </label>
                        <div class="col-md-7">
                            <input
                                type="password"
                                class="form-control"
                                name="confirm-password"
                                placeholder="********"
                                v-model="model.confirmNewPassword"
                                data-parsley-equalto="#password"
                                data-parsley-error-message="Passwords must match"
                                required
                            />
                            <span
                                class="glyphicon form-control-feedback"
                                aria-hidden="true"
                            ></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-5 offset-md-5">
                            <button
                                class="btn btn-primary"
                                type="submit"
                                @click="changePassword"
                            >
                                <i class="fas fa-check"></i>
                                {{ $t('profile.update-password') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import VeeValidate from 'vee-validate'
import Vue from 'vue'
import Loader from '../components/Loader.vue'
import CxltToaster from 'cxlt-vue2-toastr'
import 'cxlt-vue2-toastr/dist/css/cxlt-vue2-toastr.css'

Vue.use(VeeValidate)
Vue.use(CxltToaster)
VeeValidate.configure({
    events: 'change',
})
export default {
    name: 'profile',
    components: {
        Loader
    },
    data() {
        return {
            model: {
                email: '',
                name: '',
                currentPassword: '',
                newPassword: '',
                confirmNewPassword: '',
            },
        }
    },
    methods: {
        changeName: function() {
            const data = new FormData()
            data.append('email', this.model.email)
            data.append('name', this.model.name)
            this.$request(true, 'web/user/edit/basic-info', data)
                .then(resp => {
                    this.$toast.success({
                        title: 'Changes Saved',
                        message: resp.data.data.message
                    })
                });
        },
        changePassword: function() {
            const data = new FormData()
            data.append('current-password', this.model.currentPassword)
            data.append('password', this.model.newPassword)
            data.append('confirm-password', this.model.confirmNewPassword)
            this.$request(true, 'web/user/edit/password', data)
                .then(resp => {
                    this.$toast.success({
                        title: 'Changes Saved',
                        message: resp.data.data.message
                    })
                })
        },
    },
    created: function() {
        this.$request(false, 'web/user/edit').then(resp => {
            this.model.name = resp.data.data.name
            this.model.email = resp.data.data.email
        })
        this.$validator.localize({
            en: {
                messages: {
                    email: 'This value should be a valid email.',
                    required: 'This value is required.',
                },
                custom: {
                    recaptcha: {
                        required: 'Please complete the captcha.',
                    },
                },
            },
        })
    },
    beforeRouteEnter(to, from, next) {
        next(vm => {
            vm.referer = from.path
        })
    },
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
    .col.login {
        margin: 40px;

        &:first-child {
            margin-right: 20px;
        }

        &:last-child {
            margin-left: 20px;
        }

        background: #fff;
        border-radius: 0.25rem;
        border: solid 1px rgba(0, 0, 0, 0.125);

        .login-card {
            margin: 10px;
        }
    }
}

.loading {
    .loader {
        margin: 100px auto 0;
    }
}
</style>
