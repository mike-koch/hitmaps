<template>
    <div v-if="days < 2">
        {{ hours | twoDigits }}:{{ minutes | twoDigits }}:{{
            seconds | twoDigits
        }}
    </div>
    <div v-else>
        {{ $t('elusive-target.n-days', { days: days }) }}
    </div>
</template>

<script>
export default {
    props: {
        date: {
            type: String,
            required: true,
        },
    },
    computed: {
        parsedDate: function() {
            return new Date(this.date).getTime()
        },
        seconds: function() {
            return Math.trunc(((this.parsedDate - this.now) / 1000) % 60)
        },
        minutes: function() {
            return Math.trunc(((this.parsedDate - this.now) / 1000 / 60) % 60)
        },
        hours: function() {
            return Math.trunc((this.parsedDate - this.now) / 1000 / 60 / 60)
        },
        days: function() {
            return Math.trunc(
                (this.parsedDate - this.now) / 1000 / 60 / 60 / 24
            )
        },
    },
    data() {
        return {
            now: new Date(),
        }
    },
    mounted() {
        setInterval(() => {
            this.now = new Date()
        }, 1000)
    },
    filters: {
        twoDigits: function(value) {
            if (value < 10) {
                return '0' + value
            }
            return value
        },
    },
}
</script>
