jQuery(document).ready(function ($) {
  console.log("Filter script initialized");

  const $form = $("#property-filter-form");
  const $propertyList = $("#property-list");

  if (!$form.length) {
    console.error("Property filter form not found");
    return;
  }

  // Функция дебаунсинга для предотвращения частых запросов
  const debounce = (func, wait) => {
    let timeout;
    return function executedFunction(...args) {
      const later = () => {
        clearTimeout(timeout);
        func(...args);
      };
      clearTimeout(timeout);
      timeout = setTimeout(later, wait);
    };
  };

  // Функция отправки запроса
  const submitFilter = function (e) {
    if (e) {
      e.preventDefault();
    }

    console.log("Submitting filter form"); // Для отладки

    const filterData = {
      action: "filter_properties",
      nonce: $form.data("nonce"),
      house_name: $("#house_name").val(),
      district: $("#district").val(),
      floors: $("#floors").val(),
      building_type: $('input[name="building_type"]:checked').val(),
      eco_rating: $("#eco_rating").val(),
    };

    console.log("Filter data:", filterData);
    $propertyList.html(
      '<div class="text-center"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Завантаження...</span></div></div>'
    );

    $.ajax({
      url: ajax_object.ajax_url,
      type: "POST",
      data: filterData,
      success: function (response) {
        console.log("Response received:", response);
        if (response.success) {
          $propertyList.html(response.data);
        } else {
          $propertyList.html(
            '<div class="alert alert-info">Об\'єкти не знайдено</div>'
          );
        }
      },
      error: function (xhr, status, error) {
        console.error("Ajax error:", error);
        $propertyList.html(
          '<div class="alert alert-danger">Виникла помилка при завантаженні об\'єктів</div>'
        );
      },
    });
  };

  // Обработчик отправки формы
  $form.on("submit", submitFilter);

  // Автоматическая фильтрация при изменении полей
  const debouncedSubmit = debounce(submitFilter, 500);

  $('select, input[type="radio"]', $form).on("change", debouncedSubmit);
  $('input[type="text"]', $form).on("input", debouncedSubmit);
});
