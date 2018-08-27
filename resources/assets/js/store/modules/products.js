const state = {
    products: [],
    currencyRate: 0,
    cart: [],
};

const getters = {

};

const actions = {

};

const mutations = {
    addProductAtCart(state, productId) {
        let product = state.products.find(item => (item.id === productId));
        let productDataAtCart = state.cart.find(item => (item.id === productId));
        let productCountAtCart = productDataAtCart ? productDataAtCart.count : 0;
        if (product.count - productCountAtCart - 1 < 0) {
            throw new Error('Товара больше нет в наличии');
        }
        if (productDataAtCart) {
            productDataAtCart.count++;
        } else {
            state.cart.push({
                id: product.id,
                count: 1,
            });
        }
    },
    removeProductAtCart(state, productId) {
        let productsAtCart = [];
        productsAtCart = state.cart.filter(product => (product.id !== productId));
        state.cart = productsAtCart;
    },
    updateProducts(state, data) {
        let { productsData, currencyRate, productNames} = data;
        state.currencyRate = currencyRate;
        let products = [];
        productsData.forEach(function(productData) {
            let oldProduct = state.products.find(item => (item.id === productData.T));
            let productDataAtCart = state.cart.find(item => (item.id === productData.T));
            let product = {
                id: productData.T,
                name: productNames[String(productData.G)].B[String(productData.T)].N,
                price: Math.round(productData.C * state.currencyRate * 100) / 100,
                originalPrice: productData.C,
                groupId: productData.G,
                count: productData.P,
                availableCount: productDataAtCart ? productData.P - productDataAtCart.count : productData.P,
                isPriceBecomeBigger: null,
            };
            if (oldProduct) {
                let oldPrice = oldProduct.price;
                if (oldPrice !== null) {
                    if (oldPrice > product.price) {
                        product.isPriceBecomeBigger = false;
                    } else if (oldPrice < product.price) {
                        product.isPriceBecomeBigger = true;
                    } else {
                        product.isPriceBecomeBigger = null;
                    }
                }
            }
            products.push(product);
        });
        state.products = products;
    }
};

export default {
    state,
    getters,
    actions,
    mutations
}