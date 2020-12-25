<template>
    <div>
       <div class="content">
			<div class="container-fluid">	
				<!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
				<div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
					<p class="_title0">Products <Button @click="addModal=true"><Icon type="ios-add" /> Add product</Button></p>

					<div class="_overflow _table_div">
						<table class="_table">
								<!-- TABLE TITLE -->
							<tr>
								<th>ID</th>
								<th>Image</th>
								<th>Name</th>
								<th>Status</th>
								<th>Price (RM)</th>
								<th>Quantity</th>
								<th>Created at</th>
								<th>Action</th>
							</tr>
								<!-- TABLE TITLE -->


								<!-- ITEMS -->
							<tr v-for="(product,i) in productsList" :key="i" v-if="productsList.length">
								<td>{{product.id}}</td>
								<td class="table_image"><img :src ="product.productImage"/></td>
								<td>{{product.productName}}</td>
								<td>{{product.productActivated}}
								<td>{{product.productPrice}}</td>
								<td>{{product.productQuantity}}</td>
								<td>{{product.created_at,}}</td>
								<td>
									<Button size=small @click="updateStatus(product, i)" :loading="product.isDoing"><Icon type="md-git-compare" /></Button>
                                    <Button type="info" size=small @click="showEditModal(product, i)">Edit</Button>
                                    <Button type="error" size=small @click="showDeleteModal(product, i)" :loading="product.isDeleting">Delete</Button>
								</td>
							</tr>
								<!-- ITEMS -->
						</table>
					</div>
				</div>

				<!-- Add product model -->
				<Modal
					v-model="addModal"
					fullscreen title="Add Product"
					>
					<div class="center">
						<label for="data.productName"><h2>Product Name:</h2></label>
						<Input v-model="data.productName" placeholder="Enter product name"/>
						<div class="space"></div>
						<label for="data.productImage"><h2>Product Image:</h2></label>
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
								<p>Click or drag image here to upload</p>
							</div>
						</Upload>
						<div class="demo-upload-list" v-if="data.productImage">
							<img :src="`${data.productImage}`" />
							<div class="demo-upload-list-cover">
								<Icon type="ios-trash-outline" @click="deleteImage"></Icon>
							</div>
						</div>
						<div class="space"></div>
						<div>
							<label for="data.productDescription"><h2>Product Description:</h2></label>
							<Input v-model="data.productDescription" type="textarea" :autosize="{minRows: 10,maxRows: 20}" 
							placeholder="Add the desription of your product" />
						</div>
						<div class="space"></div>
						<div>
							<label for="data.productPrice"><h2>Product Price: </h2></label>
							<div>						
								<InputNumber v-model="data.productPrice" placeholder="Enter the price" style="width: 215px"></InputNumber>
							</div>

						</div>
						<div class="space"></div>
						<div>
							<label for="data.productQuantity"><h2>Product Quantity: </h2></label>
							<div>
								<InputNumber v-model="data.productQuantity" placeholder="Stock quantity" style="width: 215px" />
							</div>
						</div>
					</div>
					<div slot="footer">
						<Button type="default" @click="addModal=false">Close</Button>
						<Button type="primary" @click="addproduct" :disabled="isDoing" :loading="isDoing">{{isDoing?'Adding...': 'Add product'}}</Button>
					</div>
					
				</Modal>	
				<!-- Add product model -->

				<!-- Edit product model -->
				<Modal
					v-model="editModal"
					fullscreen title="Edit Product"
					>
					<div class="center">					
						<Input v-model="editData.productName" placeholder="Edit product name"/>
						<div class="space"></div>
						<Upload v-show="isProductImageNew"
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
								<p>Click or drag image here to upload</p>
							</div>
						</Upload>
						<div class="demo-upload-list" v-if="editData.productImage">
							<img :src="`${editData.productImage}`" />
							<div class="demo-upload-list-cover">
								<Icon type="ios-trash-outline" @click="deleteImage(false)"></Icon>
							</div>
						</div>
						<div class="space"></div>
						<div>
							<label for="editData.productDescription"><h2>Product Description:</h2></label>
							<Input v-model="editData.productDescription" type="textarea" :autosize="{minRows: 10,maxRows: 20}" 
							placeholder="Add the desription of your product" />
						</div>
						<div class="space"></div>
						<div>
							<label for="editData.productPrice"><h2>Product Price: </h2></label>
							<div>						
								<InputNumber v-model="editData.productPrice" placeholder="Enter the price" style="width: 215px"></InputNumber>
							</div>

						</div>
						<div class="space"></div>
						<div>
							<label for="editData.productQuantity"><h2>Product Quantity: </h2></label>
							<div>
								<InputNumber v-model="editData.productQuantity" placeholder="Stock quantity" style="width: 215px" />
							</div>
						</div>

					</div>
					<div slot="footer">
						<Button type="default" @click="closedEditModel">Close</Button>
						<Button type="primary" @click="editproduct" :disabled="isDoing" :loading="isDoing">{{isDoing?'Editing...': 'Edit product'}}</Button>
					</div>

				</Modal>	
				<!-- Edit product model -->

				<!-- Delete product model -->
				<Modal v-model="deleteModal" width="360">
					<p slot="header" style="color:#f60;text-align:center">
						<Icon type="ios-information-circle"></Icon>
						<span>Delete confirmation</span>
					</p>
					<div style="text-align:center">
						<p>Are you sure you want to delete it?</p>
					</div>
					<div slot="footer">
						<Button type="error" size="large" long @click="deleteproduct" :disabled="isDeleting" :loading="isDeleting">Delete</Button>
					</div>
				</Modal>
				<!-- Delete product model -->
			</div>
		</div>
    </div>
</template>

<script>
export default {
    data(){
		return{
			data:{
				productName:null,
				productImage:null,
				productDescription:null,
				productPrice:0,
				productQuantity:1,
			},
			addModal: false,
			editModal:false,
			deleteModal:false,
			isDoing: false,
			isDeleting: false,
			productsList: [],
			editData:{
				productName:null,
				productImage:null,
				productDescription:null,
				productPrice:0,
				productQuantity:1,
			},
			statusData:{
				productActivated:'active',
			},
			index : -1,
			deleteItem:{},
			deleteIndex: -1,
			token:'',
			isProductImageNew: false,
			isEditingItem: false,
			isNumber: false,

		}
	},

	methods:{
		async addproduct(){
			if(this.data.productName.trim()=='')return this.e('Product Name is required');
			if(this.data.productImage.trim()=='')return this.e('Product Image is required');
			if(this.data.productDescription.trim()=='')return this.e('Product Description is required');
			if (!this.data.productPrice)return this.e('Product Price required.');
			if (!this.data.productQuantity)return this.e('Product Quantity required.');
			this.data.productImage = `${this.data.productImage}`;
			const res = await this.callApi('post', '/create_product', this.data);
			if(res.status===201){
				this.productsList.unshift(res.data);
				this.s('product has been added successfully.');
				this.addModal=false;
				this.data.productName=null;
				this.data.productImage=null;
				this.data.productDescription=null;
				this.data.productPrice=0;
				this.data.productQuantity=1;
				this.$refs.uploads.clearFiles();
			}else{
				if(res.status==422){
					if(res.data.errors.productName){
						this.e(res.data.errors.productName[0])
					}
					if(res.data.errors.productImage){
						this.e(res.data.errors.productImage[0])
					}
					if(res.data.errors.productDescription){
						this.e(res.data.errors.productDescription[0])
					}
					if(res.data.errors.productPrice){
						this.e(res.data.errors.productPrice[0])
					}
					if(res.data.errors.productQuantity){
						this.e(res.data.errors.productQuantity[0])
					}
					
				}else{
					this.swr()
				}
				
			}
		},	
		async editproduct(){
			if(this.editData.productName.trim()=='')return this.e('Product Name is required');
			if(this.editData.productImage.trim()=='')return this.e('Product Image is required');
			if(this.editData.productDescription.trim()=='')return this.e('Product Description is required');
			if (!this.editData.productPrice)return this.e('Product Price required.');
			if (!this.editData.productQuantity)return this.e('Product Quantity required.');
			const res = await this.callApi('post', '/edit_product', this.editData);
			if(res.status===200){
				this.productsList[this.index].productName=this.editData.productName;
				this.productsList[this.index].productImage=this.editData.productImage;
				this.productsList[this.index].productDescription=this.editData.productDescription;
				this.productsList[this.index].productPrice=this.editData.productPrice;
				this.productsList[this.index].productQuantity=this.editData.productQuantity;
				this.s('Product has been edited successfully.');
				this.editModal=false;
				this.isEditingItem = false;
				this.productName=null;
				this.productImage-null;
				this.isProductImageNew=false;
				this.productDescription=null;
				this.productPrice=0;
				this.productQuantity=1;
			}else{
				if(res.status==422){
					if(res.editData.errors.productName){
						this.e(res.editData.errors.productName[0])
					}
					if(res.editData.errors.productImage){
						this.e(res.editData.errors.productImage[0])
					}
					if(res.editData.errors.productDescription){
						this.e(res.editData.errors.productDescription[0])
					}
					if(res.editData.errors.productPrice){
						this.e(res.editData.errors.productPrice[0])
					}
					if(res.editData.errors.productQuantity){
						this.e(res.editData.errors.productQuantity[0])
					}
					
				}else{
					this.swr()
				}
				
			}
		},
		showEditModal(product, index){
			let obj = {
				id : product.id,
				productName : product.productName,
				productImage : product.productImage,
				productDescription : product.productDescription,
				productPrice : product.productPrice,
				productQuantity : product.productQuantity,							
			}
			this.editModal =true;
			this.editData = obj;
			this.index = index;
			this.isEditingItem = true;
		},
		async deleteproduct(){
			this.isDeleting = true;
			// if(!confirm("Are you sure you want to delete it?"))return;
			// this.$set(product, 'isDeleting', true);
			const res = await this.callApi('post', 'delete_product', this.deleteItem);
			if(res.status===200){
				this.productsList.splice(this.deleteIndex, 1);
				this.s('Selected item is deleted successfully!')
				this.data.productName=null;
				this.data.productImage=null;
				this.data.productDescription=null;
				this.data.productPrice=0;
				this.data.productQuantity=1;
			}else{
				this.swr();
			}
			this.isDeleting = false;
			this.deleteModal = false;
		},
		showDeleteModal(product, index){
			this.deleteModal = true;
			this.deleteItem = product;
			this.deleteIndex = index;
		},
		async updateStatus(product, index){
			this.isDoing = true;
			if(product.productActivated=="inactive"){
				product.productActivated="active";
			}else{
				product.productActivated="inactive";
			}
			let obj = {
				id : product.id,
				productActivated: product.productActivated,			
			}
			this.statusData=obj;
			this.index = index;
			const res = await this.callApi('post', '/active_product', this.statusData);				
			this.productsList[this.index].productActivated=product.productActivated;
			this.isDoing = false;
		},
		handleSuccess (res, file) {
			res = `/uploads/${res}`;
      		if (this.isEditingItem) {
				return (this.editData.productImage = res);
      		}else{
				return (this.data.productImage = res);
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
				this.isProductImageNew=true;
				image=this.editData.productImage;
				this.editData.productImage='';
				this.$refs.editData.clearFiles();
			}else{// for creating
				image=this.data.productImage;
				this.data.productImage='';
				this.$refs.uploads.clearFiles();
			}
			const res = await this.callApi('post', '/delete_image', {imageName: image})
			if(res.status!=200){
				this.data.productImage=image;
				this.swr();
			}
		},
		closedEditModel(){
			this.isEditingItem=false;
			this.editModal=false;
			this.isProductImageNew=false;
		}

	},
	async created(){
		this.token = window.Laravel.csrfToken;
		const res = await this.callApi('get', '/get_products');
		if(res.status==200){
			this.productsList=res.data;
			}else{
				this.swr();
			}
	},
}
</script>