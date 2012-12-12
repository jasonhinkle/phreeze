<?xml version="1.0" encoding="UTF-8"?>
<configuration>
    <system.webServer>
        <rewrite>
            <rules>
                <rule name="phreeze" stopProcessing="true">
                    <match url="^(.+)/?$" />
                    <conditions>
                        <add input="{REQUEST_FILENAME}" matchType="IsFile" negate="true" />
                        <add input="{REQUEST_FILENAME}" matchType="IsDirectory" negate="true" />
                    </conditions>
                    <action type="Rewrite" url="index.php?_REWRITE_COMMAND={R:1}" />
                </rule>
            </rules>
        </rewrite>
    </system.webServer>
</configuration>
