<template lang="pug">
.pagination-box(v-if="pagination.next_id || pagination.last_id")
	a(href="javascript:;" class="button btn-prev" @click="ajax(lastPage)" :disabled="lastButtonDisbabled") Prev
		.dmenu__arrow
	a(href="javascript:;" class="button btn-next" @click="ajax(page)" :disabled="nextButtonDisbabled") Next
		.dmenu__arrow
</template>

<script>
export default {
    props: [ 'page', 'nextData','lastPage','pagination'],
    data() {
        return {}
    },
	computed: {
		lastButtonDisbabled(){
			return this.pagination.last_id &&  this.pagination.last_id != '' ? false : true
		},
		nextButtonDisbabled(){
			return this.pagination.next_id &&  this.pagination.next_id != '' ? false : true
		},
	},
    methods: {
        ajax(page) {
			if((!this.nextButtonDisbabled && page == this.page) || (!this.lastButtonDisbabled && page == this.lastPage))
            	this.nextData(page);
        }
    }
}
</script>

<style lang="css">
.pagination-box{
	margin-top:30px;
	display: flex;
	justify-content: space-between;
}
.pagination-box .button{
	position:relative ;
	background: #063c5d;
	color:#fff;
	height:30px;
	line-height: 30px;
	font-size:13px;
}
.pagination-box a[disabled="disabled"] {
	cursor: not-allowed;
	background: #999;
}
.pagination-box .button .dmenu__arrow{
	display:block;
	width:30px;
	height:30px;
	background-size: 25px;
}
.pagination-box .btn-prev {
	padding-left:40px;
}
.pagination-box .btn-next {
	padding-right:40px;
}
.btn-prev .dmenu__arrow{
	position: absolute;
	left:0;
	transform: rotate(180deg);
}
.btn-next .dmenu__arrow{
	position: absolute;
	right:0;
}
</style>
