import { ref } from "vue";

export default function useSelect(callback) {
  const show = ref(false);

  function onSelect(item) {
    show.value = false;
    callback(item);
  }

  return {
    show,
    onSelect,
  };
}
