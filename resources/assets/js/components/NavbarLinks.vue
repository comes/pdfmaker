<template>
    <ul>
        <li>
            <router-link :to="{ name: 'home'}">
                <i class="glyphicon glyphicon-home"></i>
            </router-link>
        </li>
        <router-link :to="{ name: 'product-list'}" tag="li">
            <a>Produkte</a>
        </router-link>
        <router-link :to="{ name: 'catalog-list'}" tag="li">
            <a>Katalog</a>
        </router-link>
    </ul>
</template>