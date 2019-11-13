<template>
    <div class="wrapper">
        <p>{{ $t('form.loading') }}</p>
        <video autoplay loop muted class="img-fluid" alt="Loading">
            <source src="/video/loader.webm" type="video/webm" />
            <source src="/video/loader.mp4" type="video/mp4" />
        </video>
    </div>
</template>

<script>
export default {
    name: 'Loader'
}
</script>

<style lang="scss" scoped>
.wrapper {
    p {
        font-weight: bolder;
    }

    background: white;
    border: solid 1px #000;
    box-shadow: 5px 5px 40px #000;
    padding: 10px;
}

video {
    width: 500px;
    object-fit: fill;
}
</style>
