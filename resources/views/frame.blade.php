@extends('layouts.app')

@section('title', 'Запись на приём')

@section('api-meta')
    <meta name="api-routes"
        content="{{ json_encode([
            'servises.index' => route('api.services.index'),
            'avalibleTime.index' => route('api.avalibleTime.index'),
            'availableWeekdays.index' => route('api.availableWeekdays'),
        ]) }}">
@endsection

@section('styles')
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            color: #333;
            margin: 0;
            padding: 18px;
            max-width: 900px;
            margin-left: auto;
            margin-right: auto;
            background-color: #fff;
        }

        h1,
        h2,
        h3 {
            color: #2c3e50;
        }

        .form-container {
            background: #f9f9f9;
            padding: 18px;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, .06);
        }

        .step {
            display: none;
        }

        .step.active {
            display: block;
        }

        .form-group {
            margin-bottom: 12px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
        }

        input,
        select,
        textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 6px;
            box-sizing: border-box;
        }

        button {
            background: #3498db;
            color: #fff;
            border: none;
            padding: 10px 14px;
            border-radius: 6px;
            cursor: pointer;
        }

        button[disabled] {
            background: #95a5a6;
            cursor: not-allowed;
        }

        .navigation {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            margin-top: 14px;
        }

        .error {
            color: #e74c3c;
            font-size: .9rem;
            margin-top: 6px;
        }

        .info-block {
            background: #e8f4fc;
            padding: 12px;
            border-radius: 6px;
        }

        .small {
            font-size: .9rem;
            color: #666;
        }

        .extra-info {
            background: #e8f1ff;
            border-left: 4px solid #5c8dff;
            padding: 12px 16px;
            border-radius: 6px;
            margin-top: 10px;
        }

        .comment-group {
            position: relative;
            margin-top: 16px;
        }

        .comment-group label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
        }

        .comment-group textarea {
            width: 100%;
            padding: 10px;
            padding-right: 55px;
            border: 1px solid #ddd;
            border-radius: 6px;
            box-sizing: border-box;
            resize: vertical;
            min-height: 100px;
            font-family: inherit;
            font-size: 1rem;
        }

        #commentCounter {
            position: absolute;
            bottom: 6px;
            right: 10px;
            font-size: 0.85rem;
            color: #888;
        }

        .comment-warning #commentCounter {
            color: #e67e22;
        }

        .comment-error #commentCounter {
            color: #e74c3c;
        }

        @media (max-width: 480px) {
            body {
                padding: 12px;
                font-size: 14px;
            }

            .form-container {
                padding: 14px;
                box-shadow: none;
                border-radius: 6px;
            }

            h1 {
                font-size: 1.4rem;
                text-align: center;
            }

            h3 {
                font-size: 1.1rem;
            }

            .text-block p.small {
                font-size: 0.9rem;
                line-height: 1.4;
            }

            .extra-info {
                background: #e8f1ff;
                border-left: 4px solid #5c8dff;
                padding: 12px 16px;
                border-radius: 6px;
                margin-top: 10px;
            }

            .form-group {
                margin-bottom: 10px;
            }

            label {
                font-size: 0.95rem;
            }

            input,
            select,
            textarea {
                font-size: 0.95rem;
                padding: 8px;
            }

            button {
                width: 100%;
                padding: 10px;
                font-size: 1rem;
            }

            .navigation {
                flex-direction: column;
                gap: 8px;
            }

            .info-block {
                padding: 10px;
                font-size: 0.95rem;
            }

            .comment-group textarea {
                min-height: 80px;
                font-size: 0.95rem;
            }

            #commentCounter {
                bottom: 4px;
                right: 8px;
                font-size: 0.8rem;
            }

            .error {
                font-size: 0.85rem;
            }
        }
    </style>

@endsection

@section('content')
    <div class="container">
        <div class="text-block">
            <h1>Записаться на приём</h1>

            <p class="small">
                Для Вашего удобства и экономии времени предлагаем возможность предварительной онлайн-записи
                на прием в органы социальной защиты населения Кузбасса.
                Запись осуществляется по месту жительства (месту пребывания) гражданина, являющегося заявителем.
            </p>

            <p class="small">
                Поля, отмеченные звездочкой (*) обязательны для заполнения.
                Подтверждение факта онлайн-записи будет направлено на электронную почту, указанную при заполнении формы.
            </p>

            <p class="small extra-info" id="info-step-1" style="display:none;">
                Если хотите получить уведомление с информацией о записи, пожалуйста, укажите почту.
            </p>

            <p class="small extra-info" id="info-step-3" style="display:none;">
                Уведомляем, что при одновременном получении нескольких государственных услуг — каждая требует
                отдельной электронной записи.
            </p>

            <p class="small extra-info" id="info-step-5" style="display:none;">
                Если по каким-либо причинам Вы не сможете посетить органы социальной защиты в выбранное время,
                запись необходимо отменить — позвоните в УСЗН. Сотрудник аннулирует предварительную запись и освободит
                зарезервированное вами время для других посетителей.
            </p>
        </div>
        <div class="form-container">
            <form id="appointmentForm" action="{{ route('subscribes.store', ['token' => $frame->token]) }}" method="POST">
                @csrf
                <input type="hidden" name="token" value="{{ $frame->token }}">

                <!-- Шаг 1 -->
                <div class="step active" id="step1">
                    <h3>1. Контактные данные</h3>

                    <div class="form-group">
                        <label for="last_name" class="required">Фамилия *</label>
                        <input id="last_name" name="last_name" type="text">
                        <div class="error" id="lastNameError"></div>
                    </div>

                    <div class="form-group">
                        <label for="first_name" class="required">Имя *</label>
                        <input id="first_name" name="first_name" type="text">
                        <div class="error" id="firstNameError"></div>
                    </div>

                    <div class="form-group">
                        <label for="middle_name" class="required">Отчество (при наличии)</label>
                        <input id="middle_name" name="middle_name" type="text">
                        <div class="error" id="middleNameError"></div>
                    </div>

                    <div class="form-group">
                        <label for="phone" class="required">Телефон *</label>
                        <input id="phone" name="phone" type="tel" placeholder="+7 (___) ___-__-__">
                        <div class="error" id="phoneError"></div>
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input id="email" name="email" type="email">
                        <div class="error" id="emailError"></div>
                    </div>
                </div>

                <!-- Шаг 2 -->
                @if ($frame->division->group->divisions->count() > 0)
                    <div class="step" id="step2">
                        <h3>2. Подразделение *</h3>
                        <div class="form-group">
                            <label for="division_id" class="required">Выберите подразделение</label>
                            <select id="division_id" name="division_id">
                                <option value="">Выберите подразделение</option>

                                @foreach ($frame->division->group->divisions as $child)
                                    <option value="{{ $child->id }}">{{ $child->name }}</option>
                                @endforeach

                            </select>
                            <div class="error" id="departmentError"></div>
                        </div>
                    </div>
                @else
                    <div class="step" id="step2">
                        <h3>2. Подразделение</h3>
                        <div class="form-group">
                            <label class="required">
                                Подразделение — {{ $frame->division->name }}
                            </label>

                            <input type="hidden" id="division_id" name="division_id" value="{{ $frame->division->id }}"
                                data-name="{{ $frame->division->name }}">
                        </div>
                    </div>


                @endif

                <!-- Шаг 3 -->
                <div class="step" id="step3">
                    <h3>3. Услуга и специалист *</h3>
                    <div class="form-group">
                        <label for="service_id" class="required">Услуга</label>
                        <select id="service_id" name="service_id">
                            <option value="">Выберите услугу</option>
                        </select>
                        <div class="error" id="serviceError"></div>
                    </div>

                    <div class="form-group">
                        <label for="worker_id" class="required">Специалист</label>
                        <select id="worker_id" name="worker_id" disabled>
                            <option value="">Сначала выберите услугу</option>
                        </select>
                        <div class="error" id="workerError"></div>
                    </div>
                </div>

                <!-- Шаг 4 -->
                <div class="step" id="step4">
                    <h3>4. Дата и время *</h3>
                    <div class="form-group">
                        <label for="date" class="required">Дата</label>
                        <input id="date" name="date" type="date" min="">
                        <div class="error" id="dateError"></div>
                    </div>
                    <div class="form-group">
                        <label for="time" class="required">Время</label>
                        <select id="time" name="time" disabled>
                            <option value="">Сначала выберите дату</option>
                        </select>
                        <div class="error" id="timeError"></div>
                    </div>
                </div>

                <!-- Шаг 5 -->
                <div class="step" id="step5">
                    <h3>5. Подтверждение</h3>
                    <div class="info-block">
                        <p><strong>ФИО:</strong> <span id="confirmName"></span></p>
                        <p><strong>Телефон:</strong> <span id="confirmPhone"></span></p>
                        <p><strong>Email:</strong> <span id="confirmEmail"></span></p>
                        <p><strong>Подразделение:</strong> <span id="confirmDepartment"></span></p>
                        <p><strong>Услуга:</strong> <span id="confirmService"></span></p>
                        <p><strong>Специалист:</strong> <span id="confirmWorker"></span></p>
                        <p><strong>Дата и время:</strong> <span id="confirmDateTime"></span></p>
                    </div>
                    <div class="form-group comment-group">
                        <label for="comment">Комментарий к записи</label>
                        <textarea id="comment" name="comment" maxlength="500" rows="4" resize="none"
                            placeholder="Введите комментарий..."></textarea>
                        <div id="commentCounter">0 / 500</div>
                    </div>
                    {{-- <div class="politic"><a href="{{ $frame->division->politic }}">Политика обработки персональных данных</a></div> --}}

                </div>

                <div class="navigation">
                    <button type="button" id="prevBtn" disabled>Назад</button>
                    <button type="button" id="nextBtn">Далее</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        (function($) {
            let currentStep = 1;
            const totalSteps = 5;

            const $service = $('#service_id'),
                $worker = $('#worker_id'),
                $time = $('#time'),
                $division = $('#division_id');

            const apiRoutes = JSON.parse($('meta[name="api-routes"]').attr('content'));

            let divisionId = {{ $frame->division->id }};

            const cache = {
                services: null,
                times: {},
                allowedDays: []
            };

            let flatpickrInstance = null;

            function initFlatpickr() {
                if (flatpickrInstance) flatpickrInstance.destroy();

                flatpickrInstance = flatpickr("#date", {
                    minDate: "today",
                    dateFormat: "Y-m-d",
                    altInput: true,
                    altFormat: "d.m.Y",
                    disable: [
                        function(date) {
                            const day = date.getDay();
                            return !cache.allowedDays.includes(day === 0 ? 7 : day);
                        }
                    ],
                    locale: "ru"
                });
            }

            const d = new Date();
            $('#date').attr('min',
                `${d.getFullYear()}-${String(d.getMonth()+1).padStart(2,'0')}-${String(d.getDate()).padStart(2,'0')}`
            );

            $('#comment').on('input', function() {
                const $counter = $('#commentCounter');
                const $group = $(this).closest('.comment-group');
                const len = $(this).val().length;
                $counter.text(`${len} / 500`);
                $group.removeClass('comment-warning comment-error');
                if (len > 499) $group.addClass('comment-error');
                else if (len > 450) $group.addClass('comment-warning');
            });

            function showStep(step) {
                $('.step').removeClass('active');
                $('#step' + step).addClass('active');

                $('#info-step-1').toggle(step === 1);
                $('#info-step-3').toggle(step === 3);
                $('#info-step-5').toggle(step === 5);

                $('#prevBtn').prop('disabled', step === 1);
                $('#nextBtn').text(step === totalSteps ? 'Отправить' : 'Далее');
                if (step === totalSteps) updateConfirmation();
            }

            function formatDateHuman(dateStr) {
                if (!dateStr) return "";
                const [y, m, d] = dateStr.split("-");
                return `${d}.${m}.${y}`;
            }

            function updateConfirmation() {
                $('#confirmName').text($('#last_name').val() + ' ' + $('#first_name').val() + ' ' + $('#middle_name')
                    .val());
                $('#confirmPhone').text($('#phone').val());
                $('#confirmEmail').text($('#email').val() || '—');

                $('#confirmDepartment').text(
                    $('#division_id').data('name') ||
                    $division.find('option:selected').text() ||
                    '—'
                );

                $('#confirmService').text($service.find('option:selected').text() || '—');
                $('#confirmWorker').text($worker.find('option:selected').text() || '—');

                $('#confirmDateTime').text(
                    (formatDateHuman($('#date').val()) || '—') +
                    ($time.val() ? ' в ' + $time.val() : '')
                );
            }

            function validateStep(step) {
                $('.error').text('');
                let ok = true;

                if (step === 1) {
                    if (!$('#last_name').val().trim()) {
                        $('#lastNameError').text('Введите фамилию');
                        ok = false;
                    }
                    if (!$('#first_name').val().trim()) {
                        $('#firstNameError').text('Введите имя');
                        ok = false;
                    }

                    const regex = /^(\+7|8|7)[\s\-]?\(?\d{3}\)?[\s\-]?\d{3}[\s\-]?\d{2}[\s\-]?\d{2}$/;
                    const phoneRe = $('#phone').val();
                    if (!$('#phone').val().trim()) {
                        $('#phoneError').text('Введите телефон');
                        ok = false;
                    }
                    else if (!regex.test(phoneRe)){
                        $('#phoneError').text('Номер не соответствует формату +7 (___) ___-__-__');
                        ok = false;
                    } 
                }

                if (step === 2 && !$division.val()) {
                    $('#departmentError').text('Выберите подразделение');
                    ok = false;
                }

                if (step === 3) {
                    if (!$service.val()) {
                        $('#serviceError').text('Выберите услугу');
                        ok = false;
                    }
                    if (!$worker.val()) {
                        $('#workerError').text('Выберите специалиста');
                        ok = false;
                    }
                }

                if (step === 4) {
                    if (!$('#date').val()) {
                        $('#dateError').text('Выберите дату');
                        ok = false;
                    }
                    if (!$time.val()) {
                        $('#timeError').text('Выберите время');
                        ok = false;
                    }
                }

                return ok;
            }

            $('#nextBtn').click(function() {
                if (!validateStep(currentStep)) return;

                if (currentStep < totalSteps) {
                    currentStep++;
                    showStep(currentStep);
                } else {
                    const date = $('#date').val();
                    const time = $('#time').val();
                    if (date && time)
                        $('#appointmentForm').append(
                            `<input type="hidden" name="start_at" value="${date} ${time}">`);
                    $('#date, #time').removeAttr('name');
                    $('#appointmentForm').submit();
                    alert('Заявка успешно отправлена!');
                }
            });

            $('#prevBtn').click(() => {
                if (currentStep > 1) {
                    currentStep--;
                    showStep(currentStep);
                }
            });

            function loadServices() {
                if (!divisionId) return;

                $service.empty().append('<option>Загрузка...</option>');
                $worker.empty().append('<option>Сначала выберите услугу</option>').prop('disabled', true);

                $.getJSON(apiRoutes['servises.index'], {
                        division: divisionId
                    })
                    .done(data => {
                        const valid = data.filter(s => s.workers && s.workers.length > 0);
                        cache.services = valid;
                        renderServices(valid);
                    })
                    .fail(() => alert('Ошибка загрузки услуг'));
            }

            function renderServices(data) {
                $service.empty().append('<option value="">Выберите услугу</option>');
                data.forEach(s => $service.append(`<option value="${s.id}">${s.name}</option>`));
            }

            $service.change(function() {
                const serviceId = $(this).val();
                $worker.prop('disabled', true).empty();

                if (!serviceId) {
                    $worker.append('<option>Сначала выберите услугу</option>');
                    return;
                }

                const selected = cache.services.find(s => s.id == serviceId);
                if (!selected || !selected.workers?.length) {
                    $worker.append('<option>Нет специалистов</option>');
                    return;
                }

                $worker.append('<option value="">Выберите специалиста</option>');
                selected.workers.forEach(w =>
                    $worker.append(`<option value="${w.id}">${w.full_name}</option>`)
                );
                $worker.prop('disabled', false);
            });

            $worker.change(function() {
                const workerId = $(this).val();
                const serviceId = $service.val();
                cache.allowedDays = [];

                if (!workerId || !serviceId) return;

                $.getJSON(apiRoutes['availableWeekdays.index'], {
                        worker: workerId,
                        service: serviceId
                    })
                    .done(days => {
                        cache.allowedDays = days.map(Number);
                        initFlatpickr();
                    })
                    .fail(() => alert('Ошибка загрузки доступных дней'));
            });

            $('#date').change(function() {
                const serviceId = $service.val(),
                    workerId = $worker.val(),
                    date = $(this).val();

                const key = `${workerId}_${serviceId}_${date}`;
                if (cache.times[key]) return renderTimes(cache.times[key]);

                $time.prop('disabled', true).empty().append('<option>Загрузка...</option>');

                $.getJSON(apiRoutes['avalibleTime.index'], {
                        worker: workerId,
                        service: serviceId,
                        date
                    })
                    .done(tData => {
                        cache.times[key] = tData;
                        renderTimes(tData);
                    })
                    .fail(() => alert('Ошибка загрузки времени.'));
            });

            function renderTimes(tData) {
                $time.empty();
                if (!tData.length) {
                    $time.append('<option>Нет доступного времени</option>');
                    return;
                }
                tData.forEach(t => $time.append(`<option value="${t}">${t}</option>`));
                $time.prop('disabled', false);
            }

            $division.change(function() {
                divisionId = $(this).val();

                cache.services = null;
                cache.times = {};
                cache.allowedDays = [];

                $service.val('');
                $worker.val('').prop('disabled', true).empty().append(
                    '<option>Сначала выберите услугу</option>');
                $('#date').val('');
                $time.val('').prop('disabled', true).empty().append('<option>Сначала выберите дату</option>');

                if (divisionId) loadServices();
            });

            showStep(1);
        })(jQuery);
    </script>
@endpush
