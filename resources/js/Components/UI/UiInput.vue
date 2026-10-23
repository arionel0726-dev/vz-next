<script setup lang="ts">
export type InputType = 'text' | 'email' | 'password' | 'tel';

interface Props {
    modelValue: string;
    type?: InputType;
    label?: string;
    placeholder?: string;
    error?: string;
    disabled?: boolean;
    required?: boolean;
    autocomplete?: string;
    id?: string;
}

const props = withDefaults(defineProps<Props>(), {
    type: 'text',
    label: undefined,
    placeholder: undefined,
    error: undefined,
    disabled: false,
    required: false,
    autocomplete: undefined,
    id: undefined,
});

const emit = defineEmits<{
    'update:modelValue': [value: string];
    blur: [event: FocusEvent];
    focus: [event: FocusEvent];
}>();

function handleInput(event: Event): void {
    emit('update:modelValue', (event.target as HTMLInputElement).value);
}
</script>

<template>
    <div class="ui-input" :class="{ 'is-error': !!error }">
        <label v-if="label" :for="id" class="ui-input__label">
            {{ label }}
        </label>

        <div class="ui-input__field">
            <span v-if="$slots.prefix" class="ui-input__prefix">
                <slot name="prefix" />
            </span>

            <input
                :id="id"
                class="ui-input__control"
                :type="type"
                :value="modelValue"
                :placeholder="placeholder"
                :disabled="disabled"
                :required="required"
                :autocomplete="autocomplete"
                @input="handleInput"
                @blur="(event) => emit('blur', event)"
                @focus="(event) => emit('focus', event)"
            >

            <span v-if="$slots.suffix" class="ui-input__suffix">
                <slot name="suffix" />
            </span>
        </div>

        <p v-if="error" class="ui-input__error">
            {{ error }}
        </p>
    </div>
</template>

<style scoped>
.ui-input {
    display: flex;
    flex-direction: column;
    gap: 0.375rem;
    width: 100%;
}

.ui-input__label {
    font-size: 0.8125rem;
    font-weight: 500;
    color: #1b1b18;
}

.ui-input__field {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    border: 1px solid #d9d9d6;
    border-radius: 0.5rem;
    padding: 0 0.75rem;
    background-color: #fff;
    transition: border-color 0.15s ease;
}

.ui-input__field:focus-within {
    border-color: #1b1b18;
}

.ui-input.is-error .ui-input__field {
    border-color: #c53030;
}

.ui-input__control {
    flex: 1;
    min-width: 0;
    border: none;
    outline: none;
    background: transparent;
    padding: 0.625rem 0;
    font-size: 0.9375rem;
    color: #1b1b18;
}

.ui-input__control:disabled {
    cursor: not-allowed;
    opacity: 0.6;
}

.ui-input__prefix,
.ui-input__suffix {
    display: inline-flex;
    align-items: center;
    color: #706f6c;
}

.ui-input__error {
    font-size: 0.8125rem;
    color: #c53030;
}
</style>
