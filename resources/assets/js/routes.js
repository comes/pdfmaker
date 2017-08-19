import HomePage from "./pages/Home.vue"
import ProductPage from "./pages/Product/ProductPage.vue"
import ProductList from "./pages/Product/ProductList.vue"
import CatalogList from "./pages/Catalog/CatalogList.vue"
import DebugPage from "./pages/DebugPage.vue"

export default [
    // routes
    { path: "/", name: "home", component: HomePage },

    // product pages
    { path: "/product/:id", name: "product-show", component: ProductPage },
    { path: "/product", name: "product-list", component: ProductList },

    // catalog pages
    { path: "/catalog/:id", name: "catalog-show", component: DebugPage, meta: {api: "/api/catalog/"} },
    { path: "/catalog", name: "catalog-list", component: CatalogList },
]