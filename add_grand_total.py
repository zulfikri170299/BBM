import re

def add_grand_total(filepath):
    with open(filepath, 'r', encoding='utf-8') as f:
        content = f.read()
    
    # 1. Add the tfoot before </table></div> inside the Kendaraan table
    tfoot_html = """
                      </tbody>
                      <tfoot class="bg-gray-100 dark:bg-gray-800 font-bold border-t-4 border-gray-400 dark:border-gray-600">
                          <tr>
                              <td colspan="5" class="px-3 py-3 text-right">TOTAL KESELURUHAN (Per Bulan)</td>
                              <td class="px-2 py-3"></td>
                              <td class="px-2 py-3 text-center text-blue-700 dark:text-blue-400" id="grand-total-b1-ptx">0</td>
                              <td class="px-2 py-3 text-center text-emerald-700 dark:text-emerald-400" id="grand-total-b1-dex">0</td>
                              <td class="px-2 py-3"></td>
                              <td class="px-2 py-3 text-center text-blue-700 dark:text-blue-400" id="grand-total-b2-ptx">0</td>
                              <td class="px-2 py-3 text-center text-emerald-700 dark:text-emerald-400" id="grand-total-b2-dex">0</td>
                              <td class="px-2 py-3"></td>
                              <td class="px-2 py-3 text-center text-blue-700 dark:text-blue-400" id="grand-total-b3-ptx">0</td>
                              <td class="px-2 py-3 text-center text-emerald-700 dark:text-emerald-400" id="grand-total-b3-dex">0</td>
                          </tr>
                          <tr class="bg-indigo-50 dark:bg-indigo-900/30">
                              <td colspan="5" class="px-3 py-3 text-right text-indigo-900 dark:text-indigo-200">TOTAL DISTRIBUSI TRIWULAN (B1 + B2 + B3)</td>
                              <td colspan="4" class="px-2 py-3 text-center text-indigo-700 dark:text-indigo-300">
                                  Pertamax: <span id="grand-total-triwulan-ptx">0</span> L
                              </td>
                              <td colspan="5" class="px-2 py-3 text-center text-indigo-700 dark:text-indigo-300">
                                  P. Dex: <span id="grand-total-triwulan-dex">0</span> L
                              </td>
                          </tr>
                          <tr class="bg-rose-50 dark:bg-rose-900/30">
                              <td colspan="5" class="px-3 py-3 text-right text-rose-900 dark:text-rose-200">MAKSIMAL DISTRIBUSI (Pembelian - Susut)</td>
                              <td colspan="4" class="px-2 py-3 text-center text-rose-700 dark:text-rose-300 leading-tight">
                                  Pertamax: <span id="maksimal-distribusi-ptx">0</span> L
                                  <br><span id="status-ptx" class="text-xs"></span>
                              </td>
                              <td colspan="5" class="px-2 py-3 text-center text-rose-700 dark:text-rose-300 leading-tight">
                                  P. Dex: <span id="maksimal-distribusi-dex">0</span> L
                                  <br><span id="status-dex" class="text-xs"></span>
                              </td>
                          </tr>
                      </tfoot>
                  </table>
    """
    # Replace the end of the table
    # The table ends with </tbody> </table> </div>
    # We will just replace </tbody> </table> with </tbody> <tfoot...> </tfoot> </table>
    
    if '<tfoot' not in content:
        content = re.sub(
            r'</tbody>\s*</table>\s*</div>\s*<div class="mt-4 flex justify-end">',
            tfoot_html + '\n              </div>\n              <div class="mt-4 flex justify-end">',
            content,
            flags=re.DOTALL
        )
    
    # 2. Add recalculateGrandTotal to JS
    js_func = """
        recalculateGrandTotal() {
            let totalB1Ptx = 0, totalB1Dex = 0;
            let totalB2Ptx = 0, totalB2Dex = 0;
            let totalB3Ptx = 0, totalB3Dex = 0;

            document.querySelectorAll('tr.kendaraan-row').forEach(tr => {
                const jenis = tr.dataset.jenis;
                const b1 = parseInt(tr.querySelector('.input-b1-total').value) || 0;
                const b2 = parseInt(tr.querySelector('.input-b2-total').value) || 0;
                const b3 = parseInt(tr.querySelector('.input-b3-total').value) || 0;

                if (jenis === 'pertamax' || jenis === 'Pertamax') {
                    totalB1Ptx += b1;
                    totalB2Ptx += b2;
                    totalB3Ptx += b3;
                } else if (jenis === 'pertamina dex' || jenis === 'Pertamina Dex' || jenis === 'pertamina_dex') {
                    totalB1Dex += b1;
                    totalB2Dex += b2;
                    totalB3Dex += b3;
                }
            });

            if(document.getElementById('grand-total-b1-ptx')) document.getElementById('grand-total-b1-ptx').innerText = totalB1Ptx;
            if(document.getElementById('grand-total-b1-dex')) document.getElementById('grand-total-b1-dex').innerText = totalB1Dex;
            if(document.getElementById('grand-total-b2-ptx')) document.getElementById('grand-total-b2-ptx').innerText = totalB2Ptx;
            if(document.getElementById('grand-total-b2-dex')) document.getElementById('grand-total-b2-dex').innerText = totalB2Dex;
            if(document.getElementById('grand-total-b3-ptx')) document.getElementById('grand-total-b3-ptx').innerText = totalB3Ptx;
            if(document.getElementById('grand-total-b3-dex')) document.getElementById('grand-total-b3-dex').innerText = totalB3Dex;

            const totalTriwulanPtx = totalB1Ptx + totalB2Ptx + totalB3Ptx;
            const totalTriwulanDex = totalB1Dex + totalB2Dex + totalB3Dex;
            
            if(document.getElementById('grand-total-triwulan-ptx')) document.getElementById('grand-total-triwulan-ptx').innerText = totalTriwulanPtx;
            if(document.getElementById('grand-total-triwulan-dex')) document.getElementById('grand-total-triwulan-dex').innerText = totalTriwulanDex;

            const pembelianPtx = parseFloat(document.querySelector('input[name="pembelian_pertamax"]').value) || 0;
            const pembelianDex = parseFloat(document.querySelector('input[name="pembelian_pertamina_dex"]').value) || 0;
            const susut = parseFloat(document.querySelector('input[name="susut_persen"]').value) || 0;

            const limitPtx = Math.floor(pembelianPtx - (pembelianPtx * (susut / 100)));
            const limitDex = Math.floor(pembelianDex - (pembelianDex * (susut / 100)));

            if(document.getElementById('maksimal-distribusi-ptx')) document.getElementById('maksimal-distribusi-ptx').innerText = limitPtx;
            if(document.getElementById('maksimal-distribusi-dex')) document.getElementById('maksimal-distribusi-dex').innerText = limitDex;

            const statusPtx = document.getElementById('status-ptx');
            if(statusPtx) {
                if(totalTriwulanPtx > limitPtx) {
                    statusPtx.innerHTML = `<span class="text-rose-600 font-bold">Melebihi batas (+${totalTriwulanPtx - limitPtx} L)</span>`;
                } else {
                    statusPtx.innerHTML = `<span class="text-emerald-600 font-bold">Aman (Sisa ${limitPtx - totalTriwulanPtx} L)</span>`;
                }
            }

            const statusDex = document.getElementById('status-dex');
            if(statusDex) {
                if(totalTriwulanDex > limitDex) {
                    statusDex.innerHTML = `<span class="text-rose-600 font-bold">Melebihi batas (+${totalTriwulanDex - limitDex} L)</span>`;
                } else {
                    statusDex.innerHTML = `<span class="text-emerald-600 font-bold">Aman (Sisa ${limitDex - totalTriwulanDex} L)</span>`;
                }
            }
        },
        recalculateAllSatkers() {
"""
    if 'recalculateGrandTotal()' not in content:
        content = content.replace('recalculateAllSatkers() {', js_func)
    
    # Add call to recalculateGrandTotal inside recalculateAllSatkers and handleTableInput (which calls recalculateSatker but needs grand total)
    if 'this.recalculateGrandTotal();' not in content:
        content = content.replace('satkerIds.forEach(id => this.recalculateSatker(id));', 'satkerIds.forEach(id => this.recalculateSatker(id));\n            this.recalculateGrandTotal();')
        content = content.replace('this.recalculateSatker(tr.dataset.satkerId);', 'this.recalculateSatker(tr.dataset.satkerId);\n                this.recalculateGrandTotal();')

    # Add listeners to Information Umum fields to trigger grand total check!
    # Because if user types in pembelian, limit should update.
    content = content.replace('name="pembelian_pertamax"', 'name="pembelian_pertamax" @input="recalculateGrandTotal()"')
    content = content.replace('name="pembelian_pertamina_dex"', 'name="pembelian_pertamina_dex" @input="recalculateGrandTotal()"')
    content = content.replace('name="susut_persen"', 'name="susut_persen" @input="recalculateGrandTotal()"')

    with open(filepath, 'w', encoding='utf-8') as f:
        f.write(content)

add_grand_total('resources/views/admin/rendis/edit.blade.php')
add_grand_total('resources/views/admin/rendis/create.blade.php')
print("Grand total logic added")
