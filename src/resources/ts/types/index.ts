export interface Product {
  width: number | null
  height: number | null
  length: number | null
  weight: number | null
  quantity: number | null
}

export interface Box {
  width: number | null
  height: number | null
  length: number | null
  weight: number | null
}

export interface AllocatedBox {
  box: Box
  products: Product[]
}

export interface PackProductResponse {
  data: AllocatedBox[]
}
