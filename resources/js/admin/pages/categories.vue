<template>
    <div>
       <div class="content">
			<div class="container-fluid">	
				<!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
				<div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
					<p class="_title0">Categories <Button @click="addModal=true"><Icon type="ios-add" /> Add category</Button></p>

					<div class="_overflow _table_div">
						<table class="_table">
								<!-- TABLE TITLE -->
							<tr>
								<th>ID</th>
								<th>Category Icon Image</th>
								<th>Category Name</th>
								<th>Created at</th>
								<th>Action</th>
							</tr>
								<!-- TABLE TITLE -->


								<!-- ITEMS -->
							<tr v-for="(category,i) in categories" :key="i" 	>
								<td>{{category.id}}</td>
								<td class="table_image"><img :src ="category.iconImage"/></td>
								<td>{{category.categoryName}}</td>	
								<td>{{category.created_at}}</td>
								<td>
                                    <Button type="info" size=small @click="showEditModal(category, i)">Edit</Button>
                                    <Button type="error" size=small @click="showDeleteModal(category, i)" :loading="category.isDeleting">Delete</Button>
								</td>
							</tr>
								<!-- ITEMS -->
						</table>
					</div>
				</div>

				<!-- Add category model -->
				<Modal
					v-model="addModal"
					title="Add category"
					:mask-closable="false">
					<Input v-model="data.categoryName" placeholder="Enter category name"/>
					<div class="space"></div>
					<Upload
						ref="uploads"
						type="drag"
						:headers="{'x-csrf-token' : token, 'X-Requested-With' : 'XMLHttpRequest'}"
						:on-success="handleSuccess"
						:on-error="handleError"
						:format="['jpg','jpeg','png','JPG', 'PNG']"
						:max-size="2048"
						:on-format-error="handleFormatError"
						:on-exceeded-size="handleMaxSize"
						action="/upload">
						<div style="padding: 20px 0">
							<Icon type="ios-cloud-upload" size="52" style="color: #3399ff"></Icon>
							<p>Click or drag files here to upload</p>
						</div>
					</Upload>
					<div class="demo-upload-list" v-if="data.iconImage">
						<img :src="`${data.iconImage}`" />
						<div class="demo-upload-list-cover">
              				<Icon type="ios-trash-outline" @click="deleteImage"></Icon>
           			 	</div>
					</div>
					<div slot="footer">
						<Button type="default" @click="addModal=false">Close</Button>
						<Button type="primary" @click="addCategory" :disabled="isDoing" :loading="isDoing">{{isDoing?'Adding...': 'Add category'}}</Button>
					</div>
				</Modal>	
				<!-- Add category model -->

				<!-- Edit category model -->
				<Modal
					v-model="editModal"
					title="Edit category"
					:mask-closable="false">
					<Input v-model="editData.categoryName" placeholder="Edit category name"/>
					<div class="space"></div>
					<Upload v-show="isIconImageNew"
						ref="editData"
						type="drag"
						:headers="{'x-csrf-token' : token, 'X-Requested-With' : 'XMLHttpRequest'}"
						:on-success="handleSuccess"
						:on-error="handleError"
						:format="['jpg','jpeg','png','JPG', 'PNG']"
						:max-size="2048"
						:on-format-error="handleFormatError"
						:on-exceeded-size="handleMaxSize"
						action="/upload">
						<div style="padding: 20px 0">
							<Icon type="ios-cloud-upload" size="52" style="color: #3399ff"></Icon>
							<p>Click or drag files here to upload</p>
						</div>
					</Upload>
					<div class="demo-upload-list" v-if="editData.iconImage">
						<img :src="`${editData.iconImage}`" />
						<div class="demo-upload-list-cover">
              				<Icon type="ios-trash-outline" @click="deleteImage(false)"></Icon>
           			 	</div>
					</div>
					<div slot="footer">
						<Button type="default" @click="closedEditModel">Close</Button>
						<Button type="primary" @click="editCategory" :disabled="isDoing" :loading="isDoing">{{isDoing?'Editing...': 'Edit category'}}</Button>
					</div>
				</Modal>	
				<!-- Edit category model -->

				<!-- Delete category model -->
				<Modal v-model="deleteModal" width="360">
					<p slot="header" style="color:#f60;text-align:center">
						<Icon type="ios-information-circle"></Icon>
						<span>Delete confirmation</span>
					</p>
					<div style="text-align:center">
						<p>Are you sure you want to delete it?</p>
					</div>
					<div slot="footer">
						<Button type="error" size="large" long @click="deleteCategory" :disabled="isDeleting" :loading="isDeleting">Delete</Button>
					</div>
				</Modal>
				<!-- Delete category model -->
			</div>
		</div>
    </div>
</template>

<script>
export default {
    data(){
		return{
			data:{
				categoryName:null,
				iconImage:null,
			},
			addModal: false,
			editModal:false,
			deleteModal:false,
			isDoing: false,
			isDeleting: false,
			categories: [],
			editData:{
				categoryName:null,
				iconImage:null,
			},
			index : -1,
			deleteItem:{},
			deleteIndex: -1,
			token:'',
			isIconImageNew: false,
			isEditingItem: false,

		}
	},

	methods:{
		async addCategory(){
			if(this.data.categoryName.trim()=='')return this.e('Category name is required');
			if(this.data.iconImage.trim()=='')return this.e('Icon Image is required');
			this.data.iconImage = `${this.data.iconImage}`;
			const res = await this.callApi('post', '/create_category', this.data);
			if(res.status===201){
				this.categories.unshift(res.data);
				this.s('Category has been added successfully.');
				this.data.categoryName=null;
				this.data.iconImage=null;
				this.$refs.uploads.clearFiles();
				this.addModal=false;
			}else{
				if(res.status==422){
					if(res.data.errors.categoryName){
						this.e(res.data.errors.categoryName[0])
					}
					if(res.data.errors.iconImage){
						this.e(res.data.errors.iconImage[0])
					}
					
				}else{
					this.swr()
				}
				
			}
		},	
		async editCategory(){
			if(this.editData.categoryName.trim()=='')return this.e('Category name is required');
			if(this.editData.iconImage.trim()=='')return this.e('Icon Image is required');
			const res = await this.callApi('post', '/edit_category', this.editData);
			if(res.status===200){
				this.categories[this.index].categoryName=this.editData.categoryName;
				this.categories[this.index].iconImage=this.editData.iconImage;
				this.s('Category has been edited successfully.');
				this.editData.categoryName=null;
				this.editData.iconImage=null;
				this.isIconImageNew=false;
				this.isEditingItem=false;
				this.$refs.editData.clearFiles();
				this.editModal=false;
			}else{
				if(res.status==422){
					if(res.editData.errors.categoryName){
						this.e(res.editData.errors.categoryName[0])
					}
					if(res.editData.errors.iconImage){
						this.e(res.editData.errors.iconImage[0])
					}
					
				}else{
					this.swr()
				}
				
			}
		},
		showEditModal(category, index){
			let obj = {
				id : category.id,
				categoryName : category.categoryName,
				iconImage : category.iconImage,
			}
			this.editModal =true;
			this.editData = obj;
			this.index = index;
			this.isEditingItem = true;
		},
		async deleteCategory(){
			this.isDeleting = true;
			// if(!confirm("Are you sure you want to delete it?"))return;
			// this.$set(category, 'isDeleting', true);
			const res = await this.callApi('post', 'delete_category', this.deleteItem);
			if(res.status===200){
				this.categories.splice(this.deleteIndex, 1);
				this.s('Selected item is deleted successfully!')
				this.data.categoryName='';
				this.data.iconImage=null;
			}else{
				this.swr();
			}
			this.isDeleting = false;
			this.deleteModal = false;
		},
		showDeleteModal(category, index){
			this.deleteModal = true;
			this.deleteItem = category;
			this.deleteIndex = index;
		},
		handleSuccess (res, file) {
			res = `/uploads/${res}`;
      		if (this.isEditingItem) {
				return (this.editData.iconImage = res);
      		}else{
				return (this.data.iconImage = res);
			}
		},
		handleError(res, file) {
		this.$Notice.warning({
			title: "The file format is incorrect",
			desc: `${file.errors.file.length ? file.errors.file[0]: "Something went wrong!"}`
		});
		},
        handleFormatError (file) {
            this.$Notice.warning({
                title: 'The file format is incorrect',
                desc: 'File format of ' + file.name + ' is incorrect, please select jpg or png.'
            });
        },
        handleMaxSize (file) {
            this.$Notice.warning({
                title: 'Exceeding file size limit',
                desc: 'File  ' + file.name + ' is too large, no more than 2M.'
            });
		},
		async deleteImage(isAdd = true) {
			let image;
			if(!isAdd){//for editing
				this.isIconImageNew=true;
				image=this.editData.iconImage;
				this.editData.iconImage='';
				this.$refs.editData.clearFiles();
			}else{// for creating
				image=this.data.iconImage;
				this.data.iconImage='';
				this.$refs.uploads.clearFiles();
			}
			const res = await this.callApi('post', '/delete_image', {imageName: image})
			if(res.status!=200){
				this.data.iconImage=image;
				this.swr();
			}
		},
		closedEditModel(){
			this.isEditingItem=false;
			this.editModal=false;
			this.isIconImageNew=false;
		}

	},
	async created(){
		this.token = window.Laravel.csrfToken;
		const res = await this.callApi('get', '/get_categories');
		if(res.status==200){
			this.categories=res.data;
			}else{
				this.swr();
			}
	},
}
</script>