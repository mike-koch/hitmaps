<template>
    <div
        class="content"
        style="background: url('/img/jpg/backgrounds/loading.jpg') no-repeat center center fixed; background-size: cover"
    >
        <header class="row">
            <div class="col text-center site-header">
                <h1>Page Not Found</h1>
            </div>
        </header>
        <div class="row text-center">
            <div class="col">
                <img
                    src="/img/404.gif"
                    alt="Janus Being Attacked With Muffins"
                />
                <br />
                <a
                    style="font-size: .6em; color: white"
                    href="https://www.reddit.com/r/HiTMAN/comments/ai5ao0/death_by_muffins/"
                >
                    u/AJNguyenYT on Reddit
                </a>
                <br />
                <br />
                <a href="/" style="color: white">
                    <i class="fas fa-arrow-left"></i>
                    Back to Home
                </a>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: '404',
    title: 'Page Not Found'
}
</script>
<style lang="scss" scoped>
h1 {
    text-transform: none;
}
p {
    color: white;
}
</style>
