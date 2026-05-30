<?php
if (!function_exists('site_base_url')) {
    require_once __DIR__ . '/environment.php';
}
$tcSiteBaseUrl = rtrim(
    ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http')
    . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost')
    . rtrim(site_base_url(), '/'),
    '/'
);
?>
<script>
document.getElementById('transfer-Form').addEventListener('submit', async function(e) {
    e.preventDefault();

    const admissionNo = document.getElementById('admissionNo').value.trim();
    const dob = document.querySelector('input[name="dob"]').value.trim();
    const resultContainer = document.getElementById('resultContainer');
    const noResult = document.getElementById('noResult');
    const resultTableBody = document.getElementById('resultTableBody');
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalBtnText = submitBtn ? submitBtn.textContent : 'Search';

    resultContainer.classList.add('hidden');
    noResult.classList.add('hidden');
    noResult.textContent = 'No data found.';
    resultTableBody.innerHTML = '';

    if (!admissionNo || !dob) {
        noResult.textContent = 'Please enter admission number and date of birth.';
        noResult.classList.remove('hidden');
        return;
    }

    if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.textContent = 'Searching...';
    }

    try {
        const response = await fetch(<?= json_encode($tcSiteBaseUrl . '/proxy/tc-proxy', JSON_UNESCAPED_SLASHES) ?>, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify({ admission_no: admissionNo, dob: dob })
        });
        const resData = await response.json();

        if (!response.ok || !resData.success || !resData.data) {
            noResult.textContent = resData.message || 'No data found.';
            noResult.classList.remove('hidden');
            return;
        }

        const matched = resData.data;
        const row = `
            <tr class="odd:bg-white even:bg-gray-200 text-gray-700 border-b text-[16px] text-center">
                <td class="px-6 py-2">${matched.student_name || ''}</td>
                <td class="px-6 py-2">${matched.admission_no || ''}</td>
                <td class="px-6 py-2">${matched.class || ''}</td>
                <td class="px-6 py-2">${matched.parent_name || ''}</td>
                <td class="px-6 py-2">${matched.dob || ''}</td>
                <td class="px-6 py-2">
                    <a href="${matched.url || '#'}" target="_blank" rel="noopener noreferrer" class="text-blue-600 underline">Download</a>
                </td>
            </tr>
        `;
        resultTableBody.insertAdjacentHTML('beforeend', row);
        resultContainer.classList.remove('hidden');
    } catch (error) {
        console.error('Error fetching TC data:', error);
        noResult.textContent = 'Something went wrong. Please try again.';
        noResult.classList.remove('hidden');
    } finally {
        if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.textContent = originalBtnText;
        }
    }
});
</script>
