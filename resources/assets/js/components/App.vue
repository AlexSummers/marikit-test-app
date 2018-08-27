<template>
    <div id="app">
    <v-app id="inspire">
        <v-navigation-drawer v-model="drawer" fixed app>
            <v-list dense>
                <v-list-tile>
                    <v-list-tile-action>
                        <v-icon>book</v-icon>
                    </v-list-tile-action>
                    <v-list-tile-content>
                        <router-link :to="{ name: 'newsList' }">Новости (Backend задача)</router-link>
                    </v-list-tile-content>
                </v-list-tile>
                <v-list-tile>
                    <v-list-tile-action>
                        <v-icon>store</v-icon>
                    </v-list-tile-action>
                    <v-list-tile-content>
                        <router-link :to="{ name: 'productsPage' }">Товары (Frontend задача)</router-link>
                    </v-list-tile-content>
                </v-list-tile>
            </v-list>
        </v-navigation-drawer>
        <v-toolbar color="indigo" dark fixed app>
            <v-toolbar-side-icon @click.stop="drawer = !drawer"></v-toolbar-side-icon>
            <v-toolbar-title>Application</v-toolbar-title>
        </v-toolbar>
        <v-content>
            <router-view></router-view>
        </v-content>
        <v-footer color="indigo" app>
            <span class="white--text">AlexSummers test app to Mirakit 2018 &copy;</span>
        </v-footer>
    </v-app>
    </div>
</template>

<script>
    export default {
        data: () => ({
            drawer: null
        }),
        props: {
            source: String
        }
    }
</script>