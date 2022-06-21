

function submitbutton (task)
{
	if (task == '')
	{
		return false;
	}
	else
	{
		var isValid = true;
		var action = task.split('.');
		if (action[1] != 'cancel' && action[1] != 'close')
		{
			var forms = document.getElementById("adminForm");
			for (var i = 0; i < forms.length; i++)
			{
				if (!document.formvalidator.isValid(forms[i]))
				{
					isValid = false;
					break;
				}
			}
		}
	
		if (isValid)
		{
			submitform(task);
			return true;
		}
		else
		{
			alert(JText._('COM_TABAPAPO_TABAPAPO_ERROR_UNACCEPTABLE',
			                     'Some values are unacceptable'));
			return false;
		}
	}
}