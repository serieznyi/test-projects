# coding=utf-8

import os
from vendor.exceptions import ExistCodeError


def exec_command(command):
    """Выполнить команду"""

    if isinstance(command, list):
        command = ' '.join(command)

    print("\033[1mExecuting the command: " + str(command) + "\033[0m")

    cmd = os.system(command)

    if os.name == 'nt':
        exitCode = 0
    else:
        exitCode = os.WEXITSTATUS(cmd)

    if exitCode != 0:
        raise ExistCodeError(exitCode)
