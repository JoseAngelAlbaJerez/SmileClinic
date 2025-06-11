<template>
    <div class="relative w-full">
        <input
        type="number"
        :value="modelValue"
        @input="$emit('update:modelValue',Number($event.target.value))"
        class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white"
        min="0"
        max="100"
        placeholder="0"
        />
        <span class="absolute inset-y-0 right-2 flex items-center text-gray-500 text-sm pointer-events-none">
            %
        </span>
    </div>
</template>
<script>
export default{
    name: 'DiscountInput',
    props:{
        modelValue:{
            type: Number,
            default: 0
        }
    }
}</script>
