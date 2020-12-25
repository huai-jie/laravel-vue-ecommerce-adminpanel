<template>
    <div>
        <h1>Here is methods page</h1>
        <h2>we can have some data here </h2>
        <p>Number: {{prod_quantity}}</p>
        <button @click="updateCounter(-1)">-</button>
        <button @click="updateCounter(1)">+</button>
        

        <div v-for="(prod, i) in products_list" :key="i" v-if="products_list.length">
            <h1>{{prod.prod_name}}</h1>
            <P>{{prod.prod_description}}</P>
        </div>

        <div>
            <h1 v-if="showItem">show this if"showItem" is true</h1>
            <h1 v-else>show this if"showItem" is false</h1>
        </div>
        <button @click=changeShowItem()>Change Show</button>
    </div>  
</template>

<script>
export default {
    data(){
        return{
            prod_quantity: 0,
            products_list : [],
            showItem: false,
        }
    },
    methods:{
        updateCounter(number){
            this.prod_quantity += number
        },
        changeShowItem(){
            this.showItem = !this.showItem
        },   
    },
    created(){
        this.prod_quantity = 100
        console.log('test');
        //get the produts from the server
        let products=[
            {prod_name:'product name 1', 'prod_description': 'product 1 description', id:1},
            {prod_name:'product name 2', 'prod_description': 'product 2 description', id:2},
            {prod_name:'product name 3', 'prod_description': 'product 3 description', id:3},
            {prod_name:'product name 4', 'prod_description': 'product 4 description', id:4},
        ]
        this.products_list = products 
    },
}
</script>