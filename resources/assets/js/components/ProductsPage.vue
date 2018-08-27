<template>
    <v-layout row wrrap>
        <v-flex xs10 offset-sm1>
            <v-text-field readonly label="Курс рубля к доллару" :value="$store.state.products.currencyRate"></v-text-field>
            <v-card>
                <v-list expand>
                    <v-list-group v-for="productGroup in productGroups" :key="productGroup.name" no-action v-model="productGroup.isActive" class="my-1">
                        <v-list-tile slot="activator">
                            <v-list-tile-content>
                                <v-list-tile-title>{{ productGroup.name }}</v-list-tile-title>
                            </v-list-tile-content>
                        </v-list-tile>

                        <v-data-table
                                :headers="productTableHeaders"
                                :items="productGroup.products"
                                hide-actions
                                class="elevation-1"
                        >
                            <template slot="items" slot-scope="props">
                                <td>{{ props.item.name }}</td>
                                <td class="text-xs-center" v-if="props.item.isPriceBecomeBigger === true" bgcolor="#b22222">{{ props.item.price }}</td>
                                <td class="text-xs-center" v-else-if="props.item.isPriceBecomeBigger === false" bgcolor="#90ee90">{{ props.item.price }}</td>
                                <td class="text-xs-center" v-else>{{ props.item.price }}</td>
                                <td class="text-xs-center">{{ props.item.availableCount }}</td>
                                <td class="text-xs-center"><v-btn @click="addProductToCart(props.item.id)">В корзину</v-btn></td>
                            </template>
                        </v-data-table>
                    </v-list-group>
                </v-list>
                <v-snackbar
                    v-model="notification.isEnabled"
                    top
                    right
                    :color="notification.type"
                    :timeout="notification.timeout"
                >
                    {{notification.message}}
                    <v-btn
                        dark
                        flat
                        @click="notification.isEnabled = false"
                    >[X]</v-btn>
                </v-snackbar>
            </v-card>
        </v-flex>

        <v-flex xs10 offset-sm1 mr-5 my-5>
        <v-list expand>
            <v-list-group no-action v-model="cartTableIsActive">
                <v-list-tile slot="activator">
                    <v-list-tile-content>
                        <v-list-tile-title>Корзина</v-list-tile-title>
                    </v-list-tile-content>
                </v-list-tile>

                <v-data-table
                    :headers="cartTableHeaders"
                    :items="cart.products"
                    hide-actions
                    class="elevation-1"
                >
                    <template slot="items" slot-scope="props">
                        <td>{{ props.item.name }}</td>
                        <td class="text-xs-center">{{ props.item.price }}</td>
                        <td class="text-xs-center">{{ props.item.count }}</td>
                        <td class="text-xs-center"><v-btn @click="removeProductFromCart(props.item.id)">Удалить</v-btn></td>
                    </template>
                    <template slot="footer">
                        <td colspan="100%">
                            <strong>Всего: {{cart.sum}} ₽</strong>
                        </td>
                    </template>
                </v-data-table>
            </v-list-group>
        </v-list>
        </v-flex>
    </v-layout>
</template>

<script>
    import Names from '../data/names'
    export default {
        data() {
            return {
                productNames: {},
                notification: {
                    isEnabled: false,
                    type: 'info',
                    message: null,
                    timeout: 5000,
                },
                productTableHeaders: [
                    {
                        text: 'Название',
                        align: 'left',
                        value: 'name'
                    },
                    { text: 'Цена (₽/Шт.)', value: 'price' },
                    { text: 'Всего', value: 'availableCount' },
                    { sortable: false},
                ],
                cartTableHeaders: [
                    {
                        text: 'Название',
                        align: 'left',
                        value: 'name'
                    },
                    { text: 'Цена (₽/Шт.)', value: 'price' },
                    { text: 'Количество', value: 'count' },
                    { sortable: false },
                ],
                cartTableIsActive: true,
                currencyRateSettings: {
                    timeout: 15000,
                    min: 20,
                    max: 80,
                },
            }
        },
        methods: {
            updateProducts() {
                let productsData = this.loadProductData().Value.Goods;
                this.$store.commit('updateProducts', {productsData: productsData, currencyRate: this.getCurrencyRate(), productNames: this.productNames});
            },
            loadProductData() {
                console.log('Loaded data.json');
                return require('../data/data.json');
            },
            sendNotification(type, message) {
                this.notification.type = type;
                this.notification.message = message;
                this.notification.isEnabled = true;
            },
            addProductToCart(productId) {
                try {
                    this.$store.commit('addProductAtCart', productId);
                    this.sendNotification('success', 'Товар успешно добавлен в корзину');
                } catch (e) {
                    this.sendNotification('error', e.message)
                }
            },
            removeProductFromCart(productId) {
                this.$store.commit('removeProductAtCart', productId);
                this.sendNotification('success', 'Товар успешно удален из корзины');
            },
            getCurrencyRate() {
                 return Math.floor(Math.random() * (this.currencyRateSettings.max - this.currencyRateSettings.min + 1)) + this.currencyRateSettings.min;
            }
        },
        created() {
            this.productNames = Names;
            this.updateProducts();
        },
        mounted () {
            setInterval(function () {
                this.updateProducts();
            }.bind(this), this.currencyRateSettings.timeout);
        },

        computed: {
            productGroups() {
                let productsGroups = {};
                let that = this;
                this.$store.state.products.products.forEach(function(product) {
                    if (!productsGroups.hasOwnProperty(product.groupId)) {
                        productsGroups[product.groupId] = {
                            id: product.groupId,
                            name: that.productNames[product.groupId].G,
                            products: [],
                            isActive: true,
                        };
                    }
                    let productAtCart = that.$store.state.products.cart.find(item => (item.id === product.id));
                    let convertedProduct = {
                        id: product.id,
                        name: product.name,
                        price: product.price,
                        availableCount: productAtCart ? product.count - productAtCart.count : product.count,
                        isPriceBecomeBigger: product.isPriceBecomeBigger,
                    };
                    productsGroups[product.groupId].products.push(convertedProduct);
                });
                return productsGroups;
            },
            cart() {
                let cartProducts = [];
                let that = this;
                let sum = 0;
                this.$store.state.products.cart.forEach(function(cartData) {
                    let product = that.$store.state.products.products.find(item => (item.id === cartData.id));
                    cartProducts.push({
                        id: cartData.id,
                        name: product.name,
                        price: product.price,
                        count: cartData.count,
                    });
                    sum = sum + product.price * cartData.count;
                });
                return {
                    products: cartProducts,
                    sum: Number(sum).toFixed(2),
                };
            },
        }
    }
</script>
