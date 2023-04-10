<script setup>
import { Bootstrap5Pagination } from 'laravel-vue-pagination';
import { ref } from 'vue';
import moment from 'moment'


const props = defineProps({
    genres: Object
});

const books = ref({});
let search = ref('');
let checkedGenres = ref(['']);
let from_date = ref('');
let to_date = ref('');


const getResults = (page = 1) => {
    console.log(checkedGenres.value);
    axios.get(`?search=${search.value}&from_date=${from_date.value}&to_date=${to_date.value}&genres=${checkedGenres.value}&page=${page}`)
        .then(({data}) => {
            books.value = data;
        });
}

getResults();
</script>

<template>
    <div class="col-md-12">
        <small>This search page uses Laravel Query Builder. Check out <a href="/books">elasticsearch</a> version</small>

        <div class="grid search">
            <div class="grid-body">
                <div class="row">
                    <!-- BEGIN FILTERS -->
                    <div class="col-md-3">
                        <h2 class="grid-title"><i class="fa fa-filter"></i> Filters</h2>
                        <hr>

                        <!-- BEGIN FILTER BY CATEGORY -->
                        <h4>By category:</h4>
                        <div class="checkbox" v-for="genre in props.genres">
                            <label><input type="checkbox" class="icheck" v-model="checkedGenres" :value="genre" :id="genre" @change="getResults()"> {{ genre }}</label>
                        </div>
                        <!-- END FILTER BY CATEGORY -->

                        <div class="padding"></div>

                        <!-- BEGIN FILTER BY DATE -->
                        <h4 class="mt-4">By publication day:</h4>
                        From
                        <div class="input-group date form_date" data-date="2014-06-14T05:25:07Z" data-date-format="dd-mm-yyyy" data-link-field="dtp_input1">
                            <input type="datetime-local" class="form-control" id="published" v-model="from_date" @change="getResults">
                            <span class="input-group-addon bg-blue"><i class="fa fa-th"></i></span>
                        </div>
                        <input type="hidden" id="dtp_input1" value="">

                        To
                        <div class="input-group date form_date" data-date="2014-06-14T05:25:07Z" data-date-format="dd-mm-yyyy" data-link-field="dtp_input2">
                            <input type="datetime-local" class="form-control" id="published" v-model="to_date" @change="getResults">
                            <span class="input-group-addon bg-blue"><i class="fa fa-th"></i></span>
                        </div>
                        <small>After selecting the date, leave the input box to apply the filters</small>
                        <input type="hidden" id="dtp_input2" value="">
                        <!-- END FILTER BY DATE -->

                    </div>
                    <!-- END FILTERS -->
                    <!-- BEGIN RESULT -->
                    <div class="col-md-9">
                        <div class="d-flex flex-row justify-content-between">
                            <h2><i class="fa fa-file-o"></i> BookStore</h2>
                            <a v-if="window.App.signedIn"  href="/books/create" class="btn btn-primary">Add Book</a>
                        </div>
                        <hr>
                        <!-- BEGIN SEARCH INPUT -->
                        <div class="input-group">
                            <input type="text" class="form-control" v-model="search" @keyup="getResults()" placeholder="Search by title, author and isbn">
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
