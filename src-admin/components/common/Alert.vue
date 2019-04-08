<template lang="pug">
b-modal(id="alertModal"
	ref="alertModal"
	@ok="handleOk"
	@cancle="handleCancel"
	:title="title")
	slot(name="desc")
</template>

<script>
export default {
    props: ['title','id', 'ok', 'cancel'],
    data() {
        return {}
    },
    methods: {
        handleOk() {
            this.ok(this.id);
        },
        handleCancel() {
            this.cancel();
        }
    }
}
</script>
