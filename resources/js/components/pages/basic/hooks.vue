<template>
    <div>
        <h1 ref="myref">Hook tutorial</h1>
        <h2>My name is {{name}}</h2>
    </div>
</template>

<script>
export default {
    data(){
        return{
            name: 'huajie'
        }
    },
    beforeCreate(){
        //most useful in auth
        //before the run the template 
        console.log(this.name);
    },
    created(){
        //call mostly initial data from it 
        //data and other function is avalaible but not html
        console.log(this.name);
    },
    mounted(){
        //all the things and function including html
        console.log(this.$refs.myref)
    }

}
</script>