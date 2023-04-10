<script setup>
import { ref } from 'vue';

const props = defineProps({
    book: Object
});

const destroy = () => {
    axios.delete('/books/'+props.book.id)
        .then(() => {
            window.location.replace("/");
        })
}
</script>

<template>
    <div class="d-flex justify-content-end" v-if="window.App.signedIn">
        <a :href="'/books/' + props.book.id + '/edit'" class="btn btn-warning mx-2">Edit</a>
        <button @click="destroy()" class="btn btn-danger">Delete</button>
    </div>
    <div class="col-md-12">

        <img :src="props.book.image" alt="Book Image" class="rounded rounded-circle h-25 ">
        <div>
            <h2 v-text="props.book.title"></h2>
            <small v-text="'-' + props.book.author"></small>
            <p v-text="props.book.genre" class="text-warning"></p>
            <p v-text="'ISBN - ' + props.book.isbn" class="text-info"></p>
            <p v-text="props.book.description"></p>
        </div>
    </div>
</template>
