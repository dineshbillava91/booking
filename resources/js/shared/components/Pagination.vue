<template>
<div class="pagination">
	<span class="page prev" v-if="showPrevPage()" @click="paginatePrevPage">prev</span>
	<span
			v-for="page in totalPages"
      v-html="page"
      :class="{'page': true, 'active':checkActive(page)}"
			@click="clickPagination(page)" :key="page"
	>
  </span>
	<span class="page next" v-if="showNextPage()" @click="paginateNextPage">next</span>
</div>
</template>

<script>
export default {
  name: 'Pagination',
  props: {
    totalPages: {
      required: true,
      type: Number
    },
    currentPage: {
      required:true
    }
  },

  methods: {
    checkActive (page) {
      return ((page) === parseInt(this.currentPage));
    },
    clickPagination (page) {
      this.$emit('paginationClick', page);
    },
    showPrevPage () {
      return (this.currentPage > 1);
    },
    showNextPage () {
      if (this.totalPages === 0) {
        return false;
      }
      return ((this.currentPage) < this.totalPages && this.totalPages !== 1);
    },
    paginatePrevPage () {
      this.clickPagination(parseInt(this.currentPage) - parseInt(1));
    },
    paginateNextPage () {
      this.clickPagination(parseInt(this.currentPage) + parseInt(1));
    }
  }
};
</script>

<style lang="scss" scoped>
.pagination {
  padding-top: 10px;
  // align-content:center;
}
.page  {
	padding: 6px 12px;
	font-size: 14px;
	color: #555;
	border-radius: 4px;
	background-color: #ffffff;
	border: solid 1px #dddddd;
	margin-right: 10px;

	&.active, &:hover {
		color: #fff;
		background: #e86154;
		border: 1px solid #e86154;
		cursor: pointer;
	}

	&.next, &.prev  {
		font-size: 14px;
		color: #e86154;
		text-transform: uppercase;
		font-family: "SourceSansPro-Semibold";
		background: transparent;
		border: 0;
		margin: 0;
		padding: 0;
	}

	&.prev {
		margin-right: 20px;
	}

	&.next {
		margin-left: 10px;
	}
}
</style>
