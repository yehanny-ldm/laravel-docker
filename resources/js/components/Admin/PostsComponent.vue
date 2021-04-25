<template>

    <div class="container">

        <div class="modal fade" id="createPost" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Nuevo Post</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div v-show="modal" class="alert alert-warning" role="alert">
                            Ha ocurrido un error al crear Post
                        </div>
                        <form>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" name="filename" class="custom-file-input" id="inputFileUpload"
                                           v-on:change="onFileChange">
                                    <label class="custom-file-label" ref="uploadFile" for="inputFileUpload">Choose
                                        file</label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="recipient-name" class="col-form-label">T&iacute;tulo:</label>
                                <input v-model="title" type="text" class="form-control" id="recipient-name">
                            </div>
                            <div class="form-group">
                                <label for="message-text" class="col-form-label">Content:</label>
                                <textarea v-model="content" class="form-control" id="message-text"></textarea>
                            </div>

                            <div class="form-group">
                                <label for="message-text" class="col-form-label">Categoría:</label>
                                <select v-model="selectCat" class="form-select" aria-label="Default select example">
                                    <option v-for="category in categories" :value="category.id">{{
                                            category.name
                                        }}
                                    </option>
                                </select>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" @click="savePost" class="btn btn-primary">Guardar</button>
                    </div>
                </div>
            </div>
        </div>

        <SpinnerComponent v-show="loading"></SpinnerComponent>

        <button v-if="token" type="button" class="btn btn-primary float-right"
                data-toggle="modal" data-target="#createPost">+ Add
            post
        </button>

        <nav aria-label="Page navigation example">
            <ul class="pagination justify-content">
                <li class="page-item">
                    <a class="page-link" v-show="current_page > 1" @click="changePage(1)" tabindex="-1"
                       aria-disabled="true">First</a>
                </li>
                <li class="page-item" @click="changePage(current_page - 1)" v-show="current_page > 1"><a
                    class="page-link" href="#">{{ current_page - 1 }}</a></li>
                <li class="page-item disabled"><a class="page-link" href="#">{{ current_page }}</a></li>
                <li class="page-item" @click="changePage(current_page + 1)" v-show="current_page + 1 <= last_page"><a
                    class="page-link" href="#">{{ current_page + 1 }}</a></li>
                <li class="page-item">
                    <a class="page-link" v-show="current_page !== last_page" @click="changePage(last_page)">Final</a>
                </li>
            </ul>
        </nav>
        <div class="row row-cols-1 row-cols-md-2 g-4">

            <div class="col" v-for="post in filteredResources" :key="post.id">
                <div class="card mb-3">
                    <img :src="'/storage/images/' + post.image" class="card-img-top" alt="" style="max-height: 250px;">

                    <div class="card-body">
                        <h5 class="card-title">{{ post.title }}</h5>
                        <p class="card-text">
                            <small class="text-muted">
                                {{ moment(post.created_at, "DD-MM-YYYY").fromNow() }}
                            </small>
                        </p>
                        <router-link
                            class="btn btn-outline-primary btn-block"
                            :to="{
                          name: 'PostDetails',
                          params: { id: post.id }
                        }">
                            Ver m&aacute;s
                        </router-link>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
import moment from "moment";
import axios from 'axios';

export default {
    name: "Posts",
    data() {
        return {
            moment: moment,
            selectCat: "",
            posts: [],
            loading: true,
            categories: [],
            category: [],
            current_page: null,
            last_page: null,
            token: this.$store.state.token,
            title: '',
            content: '',
            modal: false
        }
    },
    methods: {
        onFileChange(event) {
            this.file = event.target.files[0];
        },
        changePage(page) {
            axios
                .get('/api/auth/posts/?page=' + page, {
                    headers: {'Authorization': "Bearer " + this.token}
                })
                .then(response => {
                    this.loading = false
                    this.posts = response.data.data
                    this.current_page = response.data.current_page
                    this.last_page = response.data.last_page
                    console.log(response.data)
                })
        },
        savePost() {

            let formData = new FormData();

            formData.append("image", this.file);
            formData.append("title", this.title);
            formData.append("content_post", this.content);
            formData.append("category_id", this.selectCat);

            axios.post('api/auth/posts', formData, {
                headers: {
                    'Authorization': "Bearer " + this.token
                }
            })
                .then((res) => {
                    console.log(res);
                    this.post.image = res.data.fileName
                })
                .catch((error) => {
                    console.log(error);
                });

            /*axios
                .post('api/auth/posts', {
                    "title": this.title,
                    "content_post": this.content

                }, {
                    headers: {'Authorization': "Bearer " + this.token}
                })
                .then(response => {
                    if (error.response !== 200) {
                        this.modal = true
                    }
                    console.log(response.data)
                }).catch(function (error) {
                    this.modal = true
                    console.log(response.data)

                }
            );*/
        }
    },
    created() {

        axios
            .get('/api/auth/posts', {
                headers: {'Authorization': "Bearer " + this.token}
            })
            .then(response => {
                this.loading = false
                this.posts = response.data.data
                this.current_page = response.data.current_page
                this.last_page = response.data.last_page
                console.log(this.posts)

            })

        axios
            .get('/api/auth/categories', {
                headers: {'Authorization': "Bearer " + this.token}
            })
            .then(response => {
                this.categories = response.data
            })

        console.log(this.posts)
    },
    computed: {

        filteredResources() {

            if (this.searchQuery) {
                console.log(this.posts.filter(p => console.log(p.title)))
                return this.posts.filter((p) => p.title == this.searchQuery);
            } else {
                return this.posts;
            }
        }
    }
}
</script>

<style scoped>
a {
    cursor: pointer;
}

.card:hover {
    transform: scale(1.05);
    box-shadow: 0 10px 20px rgba(0, 0, 0, .12), 0 4px 8px rgba(0, 0, 0, .06);
}
</style>
