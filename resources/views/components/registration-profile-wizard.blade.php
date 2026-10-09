<?php
  $registrationProfile = $registrationProfile ?? ['steps' => []];
  $profileDefaults = $profileDefaults ?? [];
  $registrationAction = auth()->check()
      ? route('dashboard.profiles.store', $registrationType)
      : route($registrationType . '-profile.store');
  $registrationCancelUrl = auth()->check() ? route('dashboard.index') : route('login');
?>
@if (session('verification_notice'))
  <p role="status" style="margin-bottom:16px;padding:12px;border-radius:6px;background:#ecfdf5;color:#166534;">{{ session('verification_notice') }}</p>
@endif
@if (session('verification_error'))
  <p role="alert" style="margin-bottom:16px;padding:12px;border-radius:6px;background:#fef2f2;color:#991b1b;">{{ session('verification_error') }}</p>
@endif
<form class="reg-form profile-registration-form" data-registration-wizard method="post" action="{{ $registrationAction }}" data-cancel-url="{{ $registrationCancelUrl }}" enctype="multipart/form-data" novalidate>
  @csrf
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

      <div class="profile-fields-grid">
        <?php foreach ($step['fields'] as $fieldIndex => $field): ?>
          <?php
            $fieldType = $field['type'] ?? 'text';
            $fieldId = 'profile-' . $stepNumber . '-' . $fieldIndex;
            $fieldInputName = !empty($field['multiple']) ? $field['name'] . '[]' : ($field['name'] ?? '');
            $fieldClass = !empty($field['wide']) || $fieldType === 'checkboxes' ? ' profile-field-full' : '';
            $required = !empty($field['required']);
          ?>
          <?php if ($fieldType === 'checkboxes'): ?>
            <fieldset class="profile-checkbox-field profile-field-full">
              <legend><?= htmlspecialchars($field['label'], ENT_QUOTES, 'UTF-8') ?></legend>
              <div class="profile-checkbox-list">
                <?php foreach ($field['options'] as $option): ?>
                  <label class="profile-checkbox-item">
                    <input type="checkbox" name="<?= htmlspecialchars($option['name'], ENT_QUOTES, 'UTF-8') ?>" value="1" @checked(old($option['name'], $profileDefaults[$option['name']] ?? false))>
                    <span><?= htmlspecialchars($option['label'], ENT_QUOTES, 'UTF-8') ?></span>
                  </label>
                <?php endforeach; ?>
              </div>
            </fieldset>
          <?php else: ?>
            <div class="form-field<?= $fieldClass ?>">
              <label for="<?= $fieldId ?>">
                <?= htmlspecialchars($field['label'], ENT_QUOTES, 'UTF-8') ?>
                <?php if ($required): ?><span class="req">*</span><?php endif; ?>
              </label>
              <?php if ($fieldType === 'select'): ?>
                <select id="<?= $fieldId ?>" name="<?= htmlspecialchars($field['name'], ENT_QUOTES, 'UTF-8') ?>" <?= $required ? 'required' : '' ?>>
                  <option value=""><?= htmlspecialchars($field['placeholder'] ?? 'Select an option', ENT_QUOTES, 'UTF-8') ?></option>
                  <?php foreach ($field['options'] as $option): ?>
                    <option value="<?= htmlspecialchars((string) $option, ENT_QUOTES, 'UTF-8') ?>" @selected((string) old($field['name'], $profileDefaults[$field['name']] ?? '') === (string) $option)><?= htmlspecialchars((string) $option, ENT_QUOTES, 'UTF-8') ?></option>
                  <?php endforeach; ?>
                </select>
              <?php elseif ($fieldType === 'textarea'): ?>
                <textarea id="<?= $fieldId ?>" name="<?= htmlspecialchars($field['name'], ENT_QUOTES, 'UTF-8') ?>" rows="4" placeholder="<?= htmlspecialchars($field['placeholder'] ?? '', ENT_QUOTES, 'UTF-8') ?>" <?= $required ? 'required' : '' ?>>{{ old($field['name'], $profileDefaults[$field['name']] ?? '') }}</textarea>
              <?php else: ?>
                <?php if ($fieldType === 'tel'): ?>
                  <div class="phone-input-group">
                    @include('components.phone-country-code')
                <?php endif; ?>
                <input id="<?= $fieldId ?>" name="<?= htmlspecialchars($fieldInputName, ENT_QUOTES, 'UTF-8') ?>" type="<?= htmlspecialchars($fieldType, ENT_QUOTES, 'UTF-8') ?>" value="<?= in_array($fieldType, ['file', 'password'], true) ? '' : e(old($field['name'], $profileDefaults[$field['name']] ?? '')) ?>" placeholder="<?= htmlspecialchars($field['placeholder'] ?? '', ENT_QUOTES, 'UTF-8') ?>" <?= isset($field['accept']) ? 'accept="' . htmlspecialchars($field['accept'], ENT_QUOTES, 'UTF-8') . '"' : '' ?> <?= $fieldType === 'number' ? 'min="0" step="any"' : '' ?> <?= ($field['name'] ?? '') === 'inv_stake' ? 'max="100"' : '' ?> <?= !empty($field['multiple']) ? 'multiple' : '' ?> <?= $required ? 'required' : '' ?>>
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
