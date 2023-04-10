<script setup>
import {reactive, ref} from 'vue';
import axios from "axios";

const props = defineProps({
    genres: Object
})

const onFileChange = (e) => {
    form.image =  e.target.files[0];
}

let form = reactive({
   title: '',
   author: '',
   isbn: '',
   published: '',
   publisher: '',
   description: '',
   genre: '',
   image: '',
    errors: null
});

const submit = () => {
    axios.post('/books', {form}, {headers: {'Content-Type': 'multipart/form-data'}}
    ).then(({data}) => {
        window.location.href = data;
    }).catch(({response}) => {
        form.errors = response.data;
    });
}
</script>

<template>
    <div class="col-md-8 bg-white p-4">
        <div v-if="form.errors">
            <ul class="list-group">
                <li v-for="error in form.errors" class="list-group-item list-group-item-danger">{{ error }}</li>
            </ul>
        </div>
        <form @submit.prevent="submit">
            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control" id="title" v-model="form.title" placeholder="Enter title">
            </div>
            <div class="mb-3">
                <label for="author" class="form-label">Author</label>
                <input type="text" class="form-control" id="author" v-model="form.author" placeholder="Enter author">
            </div>
            <div class="mb-3">
                <label for="isbn" class="form-label">ISBN</label>
                <input type="text" class="form-control" id="isbn" v-model="form.isbn" placeholder="Enter isbn number">
            </div>
            <div class="mb-3">
                <label for="published" class="form-label">Published at</label>
                <input type="datetime-local" class="form-control" id="published" v-model="form.published">
            </div>
            <div class="mb-3">
                <label for="publisher" class="form-label">Publisher</label>
                <input type="text" class="form-control" id="publisher" v-model="form.publisher" placeholder="Enter publisher">
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" v-model="form.description" rows="5"></textarea>
            </div>
            <div class="mb-3">
                <label for="dropdown" class="form-label">Genre</label>
                <select class="form-select" id="dropdown" v-model="form.genre">
                    <option value="">Select an option</option>
                    <option v-for="genre in props.genres" :value="genre">{{ genre }}</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Upload Image</label>
                <input type="file" class="form-control" id="image" @change="onFileChange">
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
</template>
z
