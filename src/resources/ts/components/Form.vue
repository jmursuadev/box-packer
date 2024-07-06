<template>
  <el-form label-position="top" :model="products" class="" ref="formRef" :scroll-to-error="true">
    <el-alert type="info" :closable="false" class="!mb-5">
      <p>Dimensions must be in centimeters.</p>
    </el-alert>
    <div
      class="flex flex-row items-center border-t pt-4 w-full"
      v-for="(product, i) in products"
      :key="i"
    >
      <div class="!flex flex-row flex-wrap gap-5">
        <el-form-item
          label="Length"
          :prop="`${i}.length`"
          :rules="[{ message: 'This field is required.', required: true }]"
        >
          <el-input-number v-model="product.length" />
        </el-form-item>
        <el-form-item
          label="Width"
          :prop="`${i}.width`"
          :rules="[{ message: 'This field is required.', required: true }]"
        >
          <el-input-number v-model="product.width" />
        </el-form-item>
        <el-form-item
          label="Height"
          :prop="`${i}.height`"
          :rules="[{ message: 'This field is required.', required: true }]"
        >
          <el-input-number v-model="product.height" />
        </el-form-item>
        <el-form-item
          label="Weight (in kilograms)"
          :prop="`${i}.weight`"
          :rules="[{ message: 'This field is required.', required: true }]"
        >
          <el-input-number v-model="product.weight" />
        </el-form-item>
        <el-form-item
          label="Quantity"
          :prop="`${i}.quantity`"
          :min="1"
          :rules="[{ message: 'This field is required.', required: true }]"
        >
          <el-input-number v-model="product.quantity" />
        </el-form-item>
      </div>
      <div class="!flex items-end mb-[18px] px-5 min-w-[140px]">
        <el-button @click="addNewProduct" type="primary"
          ><el-icon><i-ep-plus /></el-icon
        ></el-button>
        <el-button v-if="i !== 0" @click="removeProduct(i)" type="danger"
          ><el-icon><i-ep-close /></el-icon
        ></el-button>
      </div>
    </div>
    <div class="flex w-full mt-10">
      <el-button type="primary" class="!px-10 !py-5" @click="submit(formRef)">Submit</el-button>
    </div>
  </el-form>
  <el-dialog v-model="dialogVisible" title="Boxes" :close-on-click-modal="false" width="80%">
    <el-table :data="boxes" :border="true" style="width: 100%">
      <el-table-column type="expand">
        <template #default="props">
          <div class="p-4">
            <h2 class="font-bold text-2xl mb-5">Products</h2>
            <el-table :data="props.row.products" :border="true">
              <el-table-column label="Length" prop="length" />
              <el-table-column label="Width" prop="width" />
              <el-table-column label="Height" prop="height" />
              <el-table-column label="Weight" prop="weight" />
              <el-table-column label="Quantity" prop="quantity" />
            </el-table>
          </div>
        </template>
      </el-table-column>
      <el-table-column label="Box Name" prop="box.name" />
      <el-table-column label="Length" prop="box.length" />
      <el-table-column label="Width" prop="box.width" />
      <el-table-column label="Height" prop="box.height" />
      <el-table-column label="Weight" prop="box.weight" />
      <el-table-column
        label="# of products"
        prop="box.products"
        :formatter="numberOfProductsFormatter"
      />
    </el-table>
  </el-dialog>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import 'element-plus/es/components/notification/style/css'
import { ElNotification } from 'element-plus'
import type { FormInstance } from 'element-plus'
import { packProduct } from '../repository/packRepository'
import type { Product } from '@/types'
import type { PackProductResponse, AllocatedBox } from '../types/index'

const products = ref<Product[]>([
  { length: null, width: null, height: null, weight: null, quantity: 1 }
])
const formRef = ref<FormInstance>()
const dialogVisible = ref<boolean>(false)
const boxes = ref<AllocatedBox[]>([])

const addNewProduct = () => {
  if (products.value.length >= 10)
    return ElNotification({
      title: 'Success',
      message: 'Maximum number of products reached.',
      type: 'info'
    })

  products.value.push({ length: null, width: null, height: null, weight: null, quantity: 1 })
}

const removeProduct = (index: number) => {
  products.value.splice(index, 1)
}

const submit = (formEl: FormInstance | undefined) => {
  if (!formEl) return
  formEl.validate(async (valid) => {
    if (valid) {
      packProduct(products.value)
        .then((response: PackProductResponse) => {
          dialogVisible.value = true
          boxes.value = response.data
        })
        .catch((err) => {
          if (err.message) {
            ElNotification({
              title: 'Error',
              message: err.message,
              type: 'error'
            })
          }
        })
    } else {
      ElNotification({
        title: 'Error',
        message: 'Form validation failed.',
        type: 'error'
      })
    }
  })
}

const numberOfProductsFormatter = (row: AllocatedBox) => {
  return row.products.reduce((acc, product) => acc + (product.quantity ?? 0), 0)
}
</script>
