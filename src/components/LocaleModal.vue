<template>
    <modal id="locale-modal"
        aria-labelledby="locale-modal-label"
        :modalTitle="$t('language-modal.change-language')"
        dismissable>
        <div class="row">
            <div @click="setLang(lang.iso)" data-dismiss="modal" class="col-md-3 col-sm-6 text-center language-option" v-for="(lang, i) in languages" :key="`Lang${i}`">
                <country-flag :country="getCountryFlag(lang.iso)" size="big" />
                <p>{{ lang.name }}</p>
            </div>
        </div>
        <i18n path="language-modal.footer" tag="p">
            <span slot="submitACorrection">
                <a href="https://translate.hitmaps.com" target="_blank">{{ $t('language-modal.submit-a-correction') }}</a>
            </span>
        </i18n>
    </modal>
</template>

<script>
    import Modal from "./Modal";
    import LanguageHelpers from "./LanguageHelpers";
    import Alert from "./Alert";
    export default {
        name: "LocaleModal",
        components: {Alert, Modal},
        computed: {
            languages() {
                return LanguageHelpers.getLanguages();
            }
        },
        methods: {
            getCountryFlag(locale) {
                return LanguageHelpers.getCountryFlag(locale)
            },
            setLang(iso) {
                this.$i18n.locale = iso;
                localStorage.locale = this.$i18n.locale;
            }
        }
    }
</script>

<style lang="scss" scoped>
    * {
        color: #000;
    }

    a {
        color: navy;

        &:hover {
            color: navy;
        }
    }

    .notice {
        margin: 0 10px;
    }

    .language-option {
        cursor: pointer;
        margin-bottom: 10px;

        &:hover {
            background: rgba(0,0,0,.1);
        }
    }
</style>