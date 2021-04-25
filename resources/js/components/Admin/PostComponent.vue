<template>

    <div class="container">

        <div class="row">
            <div class="btn-group" role="group">
                <button id="btnGroupDrop1" type="button" class="btn btn-secondary dropdown-toggle"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Opciones
                </button>
                <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                    <a class="dropdown-item" @click="edit = true" href="#">Editar</a>
                    <a class="dropdown-item" @click="deletePost" href="#">Eliminar</a>
                </div>
            </div>
        </div>
        <br>
        <div class="row">

            <div class="input-group" style="display: none">
                <div class="custom-file">
                    <input type="file" name="filename" class="custom-file-input" id="inputFileUpload"
                           v-on:change="onFileChange">
                    <label class="custom-file-label" ref="uploadFile" for="inputFileUpload">Choose file</label>
                </div>
                <div class="input-group-append">
                    <input v-on:click="uploadImage" class="btn btn-primary" value="Upload">
                </div>
            </div>

            <div class="col-md-8 col-sm-12 col-xs-12 ">
                <div class="card border-0" style="width: 100%;">
                    <input v-model="post.title" type="text" class="form-control" v-if="edit" id="title">
                    <h1 class="card-title mx-auto mt-2" v-else>
                        <b>{{ post.title }}</b>
                    </h1>

                    <h5 class="card-subtitle mb-2 text-muted mx-auto">
                        {{ moment(post.created_at).format("MMM Do YY") }}
                        <span class="dot"></span>
                        <span>{{ creator }}</span>
                        <span class="dot"></span>
                        <span>#{{ comments.length }} comentarios</span>
                    </h5>

                    <img :src="'/storage/images/' + post.image" class="card-img-top rounded mx-auto d-block"
                         style="max-width: 820px; max-height: 400px;"
                         v-on:click="$refs.uploadFile.click()"
                         alt="">

                    <div class="card-body">
                        <p class="card-text ml-3 mr-3" v-if="edit" style="font-size: 15px;">

                            <label for="content" class="col-form-label">Contenido:</label>
                            <textarea rows="10" v-model="post.content" class="form-control" id="content"></textarea>

                        </p>
                        <p class="card-text ml-3 mr-3" v-else style="font-size: 15px;">

                            {{ post.content }}

                        </p>

                        <div class="row" v-if="edit">
                            <div class="spinner-border" role="status" v-show="loading">
                            </div>
                            <div v-show="!loading" class="btn-group" role="group"
                                 aria-label="Button group with nested dropdown">
                                <button type="button" class="btn btn-primary" @click="editPost">Guardar</button>
                                <button type="button" class="btn btn-danger" @click="edit = false">Cancel</button>
                            </div>
                        </div>

                        <hr>
                        <div class="row">
                            <div class="col-md-12 ">
                                <span class="float-right">{{ comments.length }} comentarios</span>
                            </div>
                        </div>
                        <div class="form-floating row mt-2">
                            <textarea class="form-control" v-model="newComment" placeholder="Comentario"
                                      id="floatingTextarea2" style="height: 100px"></textarea>
                            <button v-on:click="saveComment" type="button" class="btn btn-secondary mt-2">Enviar
                            </button>
                        </div>
                        <hr>


                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-12 col-xs-12">
                <CategoriesComponent :postId="this.$route.params.id"></CategoriesComponent>
            </div>

            <hr>

        </div>
        <div class="row">
            <div class="col-md-8 col-sm-12 col-xs-12 mt-4">
                <CommentsComponent :postId="this.$route.params.id"></CommentsComponent>
            </div>
        </div>

    </div>

</template>

<script>
import moment from "moment";
import axios from 'axios';
import CommentsComponent from './CommentsComponent'
import CategoriesComponent from './CategoriesComponent'

export default {
    name: "Post",
    components: {
        CommentsComponent,
        CategoriesComponent
    },
    data() {
        return {
            moment: moment,
            newComment: "",
            creator: "",
            id: this.$route.params.id,
            post: [],
            comments: [],
            token: this.$store.state.token,
            edit: false,
            title: '',
            filename: '',
            file: '',
            content: '',
            loading: false,
            cancelDelete: false,
            deleteP: true
        }
    },

    methods: {
        saveComment() {
            this.loading = true

            axios
                .post('/api/auth/comments/', {
                    body: this.newComment,
                    user_id: this.post.user_id,
                    post_id: this.post.id,
                    category_id: this.category_id,
                }, {
                    headers: {
                        'Authorization': "Bearer " + this.token,
                    }
                })
                .then(response => {
                    this.loading = false
                    this.edit = false
                    console.log(this.post)
                })
        },
        onFileChange(event) {
            this.file = event.target.files[0];
            this.uploadImage()
        },
        uploadImage() {
            let formData = new FormData();

            formData.append("image", this.file);
            formData.append("id", this.post.id);

            axios.post('/api/auth/post/uploadImage', formData, {
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
        },
        editPost(e) {
            this.loading = true

            axios
                .put('/api/auth/posts/' + this.post.id, {
                    title: this.post.title,
                    content_post: this.post.content,
                }, {
                    headers: {
                        'Authorization': "Bearer " + this.token,
                    }
                })
                .then(response => {
                    this.loading = false
                    this.edit = false
                    console.log(this.post)
                })
        },
        deletePost() {
            this.edit = false;
            this.loading = true;
            if (this.deleteP) {
                axios
                    .delete('/api/auth/posts/' + this.post.id, {
                        headers: {'Authorization': "Bearer " + this.token}
                    })
                    .then(response => {
                        this.loading = false;
                        this.$router.push({
                            name: "Posts"
                        })
                        console.log(response)
                    })
            }
        }
    },

    created() {
        console.log("this.id")
        console.log(this.id)
        axios
            .get('/api/auth/posts/' + this.id, {
                headers: {'Authorization': "Bearer " + this.token}
            })
            .then(response => {
                this.post = response.data
                console.log(this.post)
                axios.get('/api/auth/users/' + this.post.user_id, {
                    headers: {
                        'Authorization': "Bearer " + this.token
                    }
                })
                    .then((res) => {
                        console.log("res")
                        console.log(res)
                        this.creator = res.data['user']
                    })
                    .catch((error) => {
                        console.log(error);
                    });
                console.log(this.post)
            });
        axios
            .get('/api/auth/comments/' + this.id, {
                headers: {'Authorization': "Bearer " + this.token}
            })
            .then(response => {
                this.comments = response.data
                console.log(this.post)
            })


    }
}
</script>

<style scoped>
.dot {
    height: 14px;
    width: 14px;
    background-color: #bbb;
    border-radius: 50%;
    display: inline-block;
}
</style>
