<?php $registrationProfile = $registrationProfile ?? ['steps' => []]; ?>
<form class="reg-form profile-registration-form" data-registration-wizard novalidate>
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
            $fieldClass = !empty($field['wide']) || $fieldType === 'checkboxes' ? ' profile-field-full' : '';
            $required = !empty($field['required']);
          ?>
          <?php if ($fieldType === 'checkboxes'): ?>
            <fieldset class="profile-checkbox-field profile-field-full">
              <legend><?= htmlspecialchars($field['label'], ENT_QUOTES, 'UTF-8') ?></legend>
              <div class="profile-checkbox-list">
                <?php foreach ($field['options'] as $option): ?>
                  <label class="profile-checkbox-item">
                    <input type="checkbox" name="<?= htmlspecialchars($option['name'], ENT_QUOTES, 'UTF-8') ?>" value="1">
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
                    <option value="<?= htmlspecialchars((string) $option, ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars((string) $option, ENT_QUOTES, 'UTF-8') ?></option>
                  <?php endforeach; ?>
                </select>
              <?php elseif ($fieldType === 'textarea'): ?>
                <textarea id="<?= $fieldId ?>" name="<?= htmlspecialchars($field['name'], ENT_QUOTES, 'UTF-8') ?>" rows="4" placeholder="<?= htmlspecialchars($field['placeholder'] ?? '', ENT_QUOTES, 'UTF-8') ?>" <?= $required ? 'required' : '' ?>></textarea>
              <?php else: ?>
                <input id="<?= $fieldId ?>" name="<?= htmlspecialchars($field['name'], ENT_QUOTES, 'UTF-8') ?>" type="<?= htmlspecialchars($fieldType, ENT_QUOTES, 'UTF-8') ?>" placeholder="<?= htmlspecialchars($field['placeholder'] ?? '', ENT_QUOTES, 'UTF-8') ?>" <?= isset($field['accept']) ? 'accept="' . htmlspecialchars($field['accept'], ENT_QUOTES, 'UTF-8') . '"' : '' ?> <?= !empty($field['multiple']) ? 'multiple' : '' ?> <?= $required ? 'required' : '' ?>>
              <?php endif; ?>
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
