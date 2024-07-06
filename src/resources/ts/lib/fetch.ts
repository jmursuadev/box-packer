// create a fetch with error handling
export const useFetch = async function <T>(input: RequestInfo, init?: RequestInit): Promise<T> {
  const response = await window
    .fetch(input, init)
    .then(async (response) => {
      if (!response.ok) {
        const error = await response.json()
        throw error
      }

      return response.json()
    })
    .then((data) => data as T)

  return response
}
