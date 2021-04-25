<template>

    <div class="container">

        <h4>Podr&iacute;a interesarte</h4>
        <div class="card mb-2" style="width: 18rem;" v-for="post in posts">
            <img :src="'/storage/images/' + post.image" class="card-img-top" alt="" style="max-width: 100%; max-height: 150px;">
            <div class="card-body">
                <h5 class="card-title">{{ post.title }}</h5>
                <p class="card-text text-muted">
                    {{ moment(post.created_at).format("MMM Do YY") }}
                </p>
                <a href="#" class="btn btn-primary">Go somewhere</a>
            </div>
        </div>

    </div>


</template>

<script>
import moment from "moment";
import axios from "axios";

export default {
    name: "CategoriesComponent",
    props: ["postId"],
    data() {
        return {
            posts: [],
            token: this.$store.state.token,
            moment: moment,
        }
    },
    methods: {},
    mounted() {
        axios
            .get('/api/auth/category/getPosts/' + this.$props.postId + "/" + 3, {
                headers: {'Authorization': "Bearer " + this.token}
            })
            .then(response => {
                this.posts = response.data
            })
    }
}
</script>

<style scoped>

</style>
