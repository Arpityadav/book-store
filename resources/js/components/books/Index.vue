<script setup>
import { Bootstrap5Pagination } from 'laravel-vue-pagination';
import { ref } from 'vue';
import moment from 'moment'


const books = ref({});
let search = ref('');


const getResults = (page = 1) => {
    axios.get(`?search=${search.value}&page=${page}`)
        .then(({data}) => {
            books.value = data;
        });
}

getResults();
</script>

<template>
    <div class="col-md-12">
        <small>This search page uses Elasticsearch. Check out <a href="/">Laravel Query Builder</a> version</small>
        <div class="grid search">
            <div class="grid-body">
                <div class="row">
                    <!-- END FILTERS -->
                    <!-- BEGIN RESULT -->
                    <div class="col-md-12">
                        <div class="d-flex flex-row justify-content-between">
                            <h2><i class="fa fa-file-o"></i> BookStore</h2>
                            <a v-if="window.App.signedIn"  href="/books/create" class="btn btn-primary">Add Book</a>
                        </div>
                        <hr>
                        <!-- BEGIN SEARCH INPUT -->
                        <div class="input-group">
                            <input type="text" class="form-control" v-model="search" @keyup="getResults()" placeholder="Search by title, author or genre">
                        </div>

                        <div class="padding"></div>

                        <div class="row">
                            <div class="col-md-6 text-right">
                                <div class="btn-group">
                                </div>
                            </div>
                        </div>

                        <!-- BEGIN TABLE RESULT -->
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <tbody>

                                    <tr v-for="book in books.data" :key="book.id">
                                            <td class="image"><img :src="book.image" alt=""></td>
                                            <td class="product"><a :href="'/books/' + book.id"><strong v-text="book.title"></strong></a><br><p v-text="book.author"></p></td>
                                            <td class="rate text-right" v-text="book.genre"></td>
                                            <td class="text-right" v-text="moment().format('ll')"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- END TABLE RESULT -->

                        <!-- BEGIN PAGINATION -->
                        <Bootstrap5Pagination
                            :data="books"
                            @pagination-change-page="getResults"
                        />
                        <!-- END PAGINATION -->
                    </div>
                    <!-- END RESULT -->
                </div>
            </div>
        </div>
    </div>
</template>
