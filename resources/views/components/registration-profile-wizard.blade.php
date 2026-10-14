<?php
  $registrationProfile = $registrationProfile ?? ['steps' => []];
  $profileDefaults = $profileDefaults ?? [];
  $registrationAction = auth()->check()
      ? route('dashboard.profiles.store', $registrationType)
      : route($registrationType . '-profile.store');
  $registrationCancelUrl = auth()->check() ? route('dashboard.index') : route('login');
  // Mirrors AuthController::NAME_TYPE_FIELDS — explicit whitelist so free-text
  // fields like company_summary/company_website never get the letters-only pattern.
  $nameTypeFields = [
      'seller_name', 'seller_designation', 'seller_company',
      'director_name', 'contact_name', 'contact_designation',
      'inv_name', 'company_name', 'company_designation',
      'mentor_name', 'mentor_company', 'mentor_designation',
      'startup_name', 'startup_designation', 'name_of_entity',
  ];
?>
@once
  <style>
    .file-upload-control { position: relative; }
    .file-upload-preview[hidden] { display: none; }
    .file-upload-preview {
      display: flex;
      flex-wrap: nowrap;
      align-items: center;
      gap: 8px;
      margin-bottom: 8px;
      padding: 6px 8px;
      border: 1px solid var(--gray-200, #e5e7eb);
      border-radius: 8px;
      background: var(--gray-50, #f9fafb);
      max-width: 100%;
    }
    .file-upload-preview img[hidden] { display: none; }
    .file-upload-thumb { position: relative; flex-shrink: 0; display: inline-flex; min-width: 28px; min-height: 28px; }
    .file-upload-preview img {
      flex-shrink: 0;
      width: 28px;
      height: 28px;
      object-fit: cover;
      border-radius: 6px;
      border: 1px solid var(--gray-200, #e5e7eb);
      background: #fff;
    }
    .file-upload-preview-name {
      flex: 1;
      min-width: 0;
      font-size: 13px;
      color: var(--gray-700, #374151);
      overflow: hidden;
      text-overflow: ellipsis;
      white-space: nowrap;
    }
    .file-upload-remove {
      position: absolute;
      top: -6px;
      right: -6px;
      flex-shrink: 0;
      width: 16px;
      height: 16px;
      display: flex;
      align-items: center;
      justify-content: center;
      border: 1.5px solid #fff;
      border-radius: 50%;
      background: var(--gray-700, #4b5563);
      color: #fff;
      font-size: 11px;
      line-height: 1;
      cursor: pointer;
    }
    .file-upload-remove:hover { background: var(--red-500, #ef4444); color: #fff; }
  </style>
@endonce
@if (session('verification_notice'))
  <p role="status" style="margin-bottom:16px;padding:12px;border-radius:6px;background:#ecfdf5;color:#166534;">{{ session('verification_notice') }}</p>
@endif
@if (session('verification_error'))
  <p role="alert" style="margin-bottom:16px;padding:12px;border-radius:6px;background:#fef2f2;color:#991b1b;">{{ session('verification_error') }}</p>
@endif
<form class="reg-form profile-registration-form" data-registration-wizard method="post" action="{{ $registrationAction }}" data-cancel-url="{{ $registrationCancelUrl }}" enctype="multipart/form-data" novalidate>
  @csrf
  @if (request()->filled('payment_order_no'))
    <input type="hidden" name="payment_order_no" value="{{ request()->query('payment_order_no') }}">
  @endif
  @if ($errors->any())
    <div role="alert" class="field-error">
      Please correct the highlighted fields before submitting your profile. Each error is shown below its field.
    </div>
  @endif
  <?php foreach ($registrationProfile['steps'] as $stepIndex => $step): ?>
    <?php $stepNumber = $stepIndex + 1; ?>
    <section class="registration-step-panel" data-step="<?= $stepNumber ?>" <?= $stepNumber === 1 ? '' : 'hidden' ?>>
      <div class="registration-step-heading">
        <span>Step <?= $stepNumber ?> of <?= count($registrationProfile['steps']) ?></span>
        <h3><?= htmlspecialchars($step['title'], ENT_QUOTES, 'UTF-8') ?></h3>
        <p><?= htmlspecialchars($step['description'], ENT_QUOTES, 'UTF-8') ?></p>
      </div>

      <?php
        // Build a checkbox-name => checked map for this step up front, so a
        // field with a 'group' key (added after the checkbox in the array)
        // can know its initial visibility before we ever render the checkbox.
        $stepGroupChecked = [];
        foreach ($step['fields'] as $field) {
          if (($field['type'] ?? null) === 'checkboxes') {
            foreach ($field['options'] as $option) {
              $stepGroupChecked[$option['name']] = (bool) old($option['name'], $profileDefaults[$option['name']] ?? false);
            }
          }
        }
      ?>
      <div class="profile-fields-grid">
        <?php foreach ($step['fields'] as $fieldIndex => $field): ?>
          <?php
            $fieldType = $field['type'] ?? 'text';
            $fieldId = 'profile-' . $stepNumber . '-' . $fieldIndex;
            $fieldInputName = !empty($field['multiple']) ? $field['name'] . '[]' : ($field['name'] ?? '');
            $fieldClass = !empty($field['wide']) || $fieldType === 'checkboxes' ? ' profile-field-full' : '';
            $required = !empty($field['required']);
            $fieldGroup = $field['group'] ?? null;
            $fieldGroupAttr = $fieldGroup ? ' data-requirement-group="' . htmlspecialchars($fieldGroup, ENT_QUOTES, 'UTF-8') . '"' : '';
            $fieldGroupStyle = $fieldGroup && empty($stepGroupChecked[$fieldGroup]) ? ' style="display:none"' : '';
          ?>
          <?php if ($fieldType === 'checkboxes'): ?>
            <fieldset class="profile-checkbox-field profile-field-full" <?= !empty($field['exclusive']) ? 'data-exclusive-group' : '' ?>>
              <legend><?= htmlspecialchars($field['label'], ENT_QUOTES, 'UTF-8') ?></legend>
              <div class="profile-checkbox-list">
                <?php foreach ($field['options'] as $option): ?>
                  <label class="profile-checkbox-item">
                    <input type="checkbox" name="<?= htmlspecialchars($option['name'], ENT_QUOTES, 'UTF-8') ?>" value="1" class="requirement-checkbox" data-requirement-target="<?= htmlspecialchars($option['name'], ENT_QUOTES, 'UTF-8') ?>" @checked(old($option['name'], $profileDefaults[$option['name']] ?? false))>
                    <span><?= htmlspecialchars($option['label'], ENT_QUOTES, 'UTF-8') ?></span>
                  </label>
                <?php endforeach; ?>
              </div>
            </fieldset>
          <?php else: ?>
            <div class="form-field<?= $fieldClass ?>"<?= $fieldGroupAttr ?><?= $fieldGroupStyle ?>>
              <label for="<?= $fieldId ?>">
                <?= htmlspecialchars($field['label'], ENT_QUOTES, 'UTF-8') ?>
                <?php if ($required): ?><span class="req">*</span><?php endif; ?>
              </label>
              <?php $errorClass = $errors->has($field['name'] ?? '') ? ' has-error' : ''; ?>
              <?php if ($fieldType === 'select'): ?>
                <select id="<?= $fieldId ?>" class="<?= $errorClass ?>" name="<?= htmlspecialchars($field['name'], ENT_QUOTES, 'UTF-8') ?>" <?= $required ? 'required' : '' ?>>
                  <option value=""><?= htmlspecialchars($field['placeholder'] ?? 'Select an option', ENT_QUOTES, 'UTF-8') ?></option>
                  <?php foreach ($field['options'] as $option): ?>
                    <?php
                      $fieldDefault = $profileDefaults[$field['name']]
                          ?? (str_ends_with($field['name'], 'country') ? 'United Kingdom' : '');
                    ?>
                    <option value="<?= htmlspecialchars((string) $option, ENT_QUOTES, 'UTF-8') ?>" @selected((string) old($field['name'], $fieldDefault) === (string) $option)><?= htmlspecialchars((string) $option, ENT_QUOTES, 'UTF-8') ?></option>
                  <?php endforeach; ?>
                </select>
              <?php elseif ($fieldType === 'textarea'): ?>
                <textarea id="<?= $fieldId ?>" class="<?= $errorClass ?>" name="<?= htmlspecialchars($field['name'], ENT_QUOTES, 'UTF-8') ?>" rows="4" placeholder="<?= htmlspecialchars($field['placeholder'] ?? '', ENT_QUOTES, 'UTF-8') ?>" <?= $required ? 'required' : '' ?>>{{ old($field['name'], $profileDefaults[$field['name']] ?? '') }}</textarea>
              <?php else: ?>
                <?php if ($fieldType === 'tel'): ?>
                  <div class="phone-input-group">
                    @include('components.phone-country-code')
                <?php endif; ?>
                <?php if ($fieldType === 'file'): ?>
                  <div class="file-upload-control" data-file-upload-control>
                  <div class="file-upload-preview" data-file-upload-preview hidden>
                    <span class="file-upload-thumb">
                      <img src="" alt="Selected file preview" data-file-preview-image hidden>
                      <button type="button" class="file-upload-remove" data-file-upload-remove aria-label="Remove selected file">&times;</button>
                    </span>
                    <span class="file-upload-preview-name" data-file-preview-name></span>
                  </div>
                <?php endif; ?>
                <input id="<?= $fieldId ?>" class="<?= $errorClass ?>" name="<?= htmlspecialchars($fieldInputName, ENT_QUOTES, 'UTF-8') ?>" type="<?= htmlspecialchars($fieldType, ENT_QUOTES, 'UTF-8') ?>" value="<?= in_array($fieldType, ['file', 'password'], true) ? '' : e(old($field['name'], $profileDefaults[$field['name']] ?? '')) ?>" placeholder="<?= htmlspecialchars($field['placeholder'] ?? '', ENT_QUOTES, 'UTF-8') ?>" <?= isset($field['accept']) ? 'accept="' . htmlspecialchars($field['accept'], ENT_QUOTES, 'UTF-8') . '"' : '' ?> <?= $fieldType === 'file' ? 'data-max-size-mb="1" data-file-input' : '' ?> <?= $fieldType === 'number' ? 'min="0" step="any"' : '' ?> <?= ($field['name'] ?? '') === 'inv_stake' ? 'max="100"' : '' ?> <?= $fieldType === 'tel' ? 'pattern="07[0-9]{9}" title="Enter a valid UK mobile number starting with 07 (11 digits)."' : '' ?> <?= ($fieldType === 'text' && in_array($field['name'] ?? '', $nameTypeFields, true)) ? 'pattern="[A-Za-z\s]+" title="Name should only contain letters and spaces"' : '' ?> <?= !empty($field['multiple']) ? 'multiple' : '' ?> <?= $required ? 'required' : '' ?>>
                <?php if ($fieldType === 'file'): ?>
                  <p class="upload-hint">Max 1MB<?= isset($field['accept']) ? ' · ' . htmlspecialchars(strtoupper(str_replace(['.', ','], ['', ', '], $field['accept'])), ENT_QUOTES, 'UTF-8') : '' ?></p>
                  </div>
                <?php endif; ?>
                <?php if ($fieldType === 'tel'): ?>
                  </div>
                <?php endif; ?>
              <?php endif; ?>
              @error($field['name'] ?? '')
                <p class="field-error" data-registration-error="{{ $field['name'] }}">{{ $message }}</p>
              @enderror
            </div>
          <?php endif; ?>
        <?php endforeach; ?>
      </div>

      <div class="form-actions profile-step-actions">
        <button type="button" class="btn-cancel" data-cancel-wizard>Cancel</button>
        <?php if ($stepNumber > 1): ?>
          <button type="button" class="btn-cancel" data-prev-step>Back</button>
        <?php endif; ?>
        <span class="wizard-step-status" data-step-status>Step <?= $stepNumber ?> of <?= count($registrationProfile['steps']) ?></span>
        <?php if ($stepNumber < count($registrationProfile['steps'])): ?>
          <button type="button" class="btn-save" data-next-step>Save &amp; Continue</button>
        <?php else: ?>
          <button type="submit" class="btn-save">Submit Profile</button>
        <?php endif; ?>
      </div>
    </section>
  <?php endforeach; ?>
</form>
