<template>
    <div>
        <div v-if="loading"> Data is loading....</div>
        <div class = "container" v-else>
            <div class="row" v-for="row in rows" :key="'row' + row">
                <div class="col d-flex align-items-stretch" v-for="(bookable,index) in bookablesInRow(row)" :key="'row'+row+index">
                     <bookable-list-item class="col-sm"
                    v-bind = bookable
                    >
                    </bookable-list-item>
                </div> 
                <div class="col" v-for="poo in placeholderInRows(row)" :key="'p' + poo"></div>
            </div>
            <div>
                <pagination
                    v-if="totalPages"
                    :totalPages="totalPages"
                    :currentPage="currentPage"
                    @paginationClick="getPaginatedData"
                ></pagination>
            </div>
        </div>
        
        <!-- <bookable-list-item v-if="bookable2" :item-title="bookable2.title" :item-content ="bookable2.content" :price = "2000"></bookable-list-item>
        <bookable-list-item v-if="bookable3" :item-title="bookable3.title" :item-content ="bookable3.content" :price = "2000"></bookable-list-item> -->
    </div>
</template>

<script>
import BookableListItem from "./BookableListItem"
import Pagination from "../shared/components/Pagination"

export default {
    components:{
        BookableListItem,
        Pagination
    },
    data() {
        return {
            // bookable1 : {
            //     'title' : "Hannah 1",
            //     'content' : "Part 1 hannah's tape stories"
            // },
            // bookable2 : {
            //     'title' : "Hannah 2",
            //     'content' : "Part 2 hannah's tape stories"
            // }
            bookables: null,
            loading : false,
            columns: 3,
            totalPages: null,
            currentPage: null,
            error:false
        }
    },
    created() {
        this.loading = true;
        this.getPaginatedData(1)
    },
    computed: {
        rows() {
            return this.bookables && Math.ceil(this.bookables.length / this.columns)
        }
    },
    methods: {
        bookablesInRow(row) {
            return this.bookables.slice((row - 1) * this.columns, row * this.columns)
        },
        placeholderInRows(row) {
            return this.columns - this.bookablesInRow(row).length
        },
        getPaginatedData(page) {
            const request = axios.get(`/api/bookables?page=`+page).then(response => {
                this.bookables = response.data.data
                this.totalPages = response.data.meta.total / response.data.meta.per_page
                this.currentPage = response.data.meta.current_page
                this.loading = false;
            }).catch(response => console.log(response));
        }
    },
}
</script>