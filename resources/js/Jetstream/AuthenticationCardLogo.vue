<template>
    <Link :href="'/'">
        <img src="../../images/ismp-logo.png" alt="">
    </Link>
</template>

<script>
    import { defineComponent } from 'vue'
    import { Link } from '@inertiajs/inertia-vue3';

    export default defineComponent({
        components: {
            Link,
        },
    })
</script>
