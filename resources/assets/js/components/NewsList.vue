<template>
    <v-card>
        <v-container fluid grid-list-lg>
            <v-layout row wrap>
                <v-flex xs12 v-for="news in newsList" :key="news.id">
                    <v-card>
                        <v-card-title primary-title>
                            <div class="headline">{{news.title}}</div>
                        </v-card-title>
                        <v-card-text>
                            <div>{{news.shortContent}}...</div>
                        </v-card-text>
                        <v-card-actions>
                            <v-btn><router-link :to="{ name: 'newsPage', params: { id: news.id }}">Подробнее</router-link></v-btn>
                        </v-card-actions>
                    </v-card>
                </v-flex>
            </v-layout>
        </v-container>
    </v-card>
</template>

<script>
    import axios from 'axios';
    export default {
        name: "News",
        data () {
            return {
                newsList: {}
            }
        },
        methods: {
            loadNews() {
                let that = this;
                axios.get('/load-news').then(function(response) {
                    if (response.data.status === 'success') {
                        that.newsList = response.data.data;
                    }
                })
            }
        },
        beforeMount() {
            this.loadNews();
        }
    }
</script>