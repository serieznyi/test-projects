# coding=utf-8

import os


class Environment:
    LOCAL = 'local'

    @staticmethod
    def read(env_file):
        with open(env_file) as file:
            lines = file.read().splitlines()

        env_local = {}
        for line in lines:
            if not line:
                continue

            parts = line.split("=", 1)
            env_local[parts[0]] = parts[1]

        return env_local