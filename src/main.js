import Vue from 'vue'
import App from './App.vue'
import router from './router'
import axios from 'axios'
import store from './store/store'
import titleMixin from './util/title'
import ImageLoader from './plugins/ImageLoader'
import i18n from './i18n'
import CountryFlag from 'vue-country-flag'

axios.defaults.withCredentials = true

Vue.config.productionTip = false
//For Copyright
Vue.prototype.$currentYear = new Date().getFullYear()
Vue.prototype.$http = axios
Vue.prototype.$domain =
    document.location.protocol + '//' + window.location.hostname

//Better method to deciding what type of request
Vue.prototype.$request = (post, endpoint, data) => {
    var header = {}
    if (localStorage.getItem('token') != null) {
        header = {
            Authorization: 'Bearer ' + localStorage.getItem('token'),
        }
    }
    return new Promise((resolve, reject) => {
        if (post) {
            axios
                .post(Vue.prototype.$domain + '/api/' + endpoint, data, {
                    headers: header,
                })
                .then(resp => {
                    if (resp.data.token != null) {
                        localStorage.setItem('token', resp.data.token)
                    }
                    resolve(resp)
                })
        } else {
            axios
                .get(Vue.prototype.$domain + '/api/' + endpoint, {
                    headers: header,
                })
                .then(resp => {
                    if (resp.data.token != null) {
                        localStorage.setItem('token', resp.data.token)
                    }
                    resolve(resp)
                })
        }
    })
}

Vue.component('country-flag', CountryFlag);

Vue.mixin(titleMixin)
Vue.use(ImageLoader)

/**
 * Enable Bootstrap tooltips using Vue directive
 * @author Vitim.us
 * @see https://gist.github.com/victornpb/020d393f2f5b866437d13d49a4695b47
 * @example
 *   <button v-tooltip="foo">Hover me</button>
 *   <button v-tooltip.click="bar">Click me</button>
 *   <button v-tooltip.html="baz">Html</button>
 *   <button v-tooltip:top="foo">Top</button>
 *   <button v-tooltip:left="foo">Left</button>
 *   <button v-tooltip:right="foo">Right</button>
 *   <button v-tooltip:bottom="foo">Bottom</button>
 *   <button v-tooltip:auto="foo">Auto</button>
 *   <button v-tooltip:auto.html="clock" @click="clock = Date.now()">Updating</button>
 *   <button v-tooltip:auto.html.live="clock" @click="clock = Date.now()">Updating Live</button>
 */
Vue.directive('tooltip', {
    bind: function bsTooltipCreate(el, binding) {
        let trigger = 'hover focus';
        binding.modifiers.html = false;

        if (binding.modifiers.focus || binding.modifiers.hover || binding.modifiers.click) {
            const t = [];
            if (binding.modifiers.focus) t.push('focus');
            if (binding.modifiers.hover) t.push('hover');
            if (binding.modifiers.click) t.push('click');
            trigger = t.join(' ');
        }
        $(el).tooltip({
            title: binding.value,
            placement: binding.arg,
            trigger: trigger,
            html: binding.modifiers.html
        });
    },
    update: function bsTooltipUpdate(el, binding) {
        const $el = $(el);
        $el.attr('title', binding.value).tooltip();

        const data = $el.data('bs.tooltip');
        if (binding.modifiers.live) { // update live without flickering (but it doesn't reposition)
            if (data.$tip) {
                if (data.options.html) data.$tip.find('.tooltip-inner').html(binding.value);
                else data.$tip.find('.tooltip-inner').text(binding.value);
            }
        } else {
            if (data.inState !== undefined && (data.inState.hover || data.inState.focus || data.inState.click)) {
                $el.tooltip('show');
            }
        }
    },
    unbind(el, binding) {
        $(el).tooltip('dispose');
    },
});

const langMixin = {
    methods: {
        lang: function(key, defaultString) {
            if (this.$t && this.$te) {
                if (this.$te(key)) {
                    return this.$t(key)
                }

                console.warn("[i18n] Could not find i18n entry for key: '" + key + "'. Using '" + defaultString + "' instead.");
                return defaultString;
            }
            console.error('$t or $te is undefined!');

            if (defaultString) {
                console.warn("[i18n] Could not find i18n entry for key: '" + key + "'. Using '" + defaultString + "' instead.");
                return defaultString;
            }
            console.warn("[i18n] Could not find i18n entry for key: '" + key + "', and defaultString is undefined.");
            return key;
        }
    }
};

Vue.mixin(langMixin);

new Vue({
    store,
    router,
    i18n,
    render: h => h(App)
}).$mount('#app');