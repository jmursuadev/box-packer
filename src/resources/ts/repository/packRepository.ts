import type { PackProductResponse, Product } from '@/types'
import { useFetch } from '../lib/fetch'

export const packProduct = async (products: Product[]): Promise<PackProductResponse> => {
  return useFetch<PackProductResponse>('/api/package/pack', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      Accept: 'application/json'
    },
    body: JSON.stringify({ products })
  })
}
