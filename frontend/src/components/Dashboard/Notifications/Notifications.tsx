"use client";

import { get, put } from "@/lib/backendApi";
import {
  Alert,
  Box,
  Button,
  Checkbox,
  FormControlLabel,
  TextField,
} from "@mui/material";
import React, { FormEvent, useEffect, useState } from "react";
import useSWR from "swr";

const fetcher = (url: string) => get(url);

interface NotificationsData {
  telegram_bot_active: string;
  telegram_bot_api_key: string;
  telegram_channel_id: string;
  telegram_notify_backups: string;
  telegram_notify_migrations: string;
}

interface NotificationsProps {}

function Notifications({}: NotificationsProps) {
  const { data, error, isLoading, mutate } = useSWR(
    "/telegram/get-settings",
    fetcher
  );

  const [isTelegramNotificationsEnabled, setIsTelegramNotificationsEnabled] =
    useState("false");
  const [apiKey, setApiKey] = useState("");
  const [channelId, setChannelId] = useState("");
  const [notifyBackups, setNotifyBackups] = useState("false");
  const [notifyMigrations, setNotifyMigrations] = useState("false");
  const [timer1Id, setTimer1Id] = useState<NodeJS.Timeout | undefined>(
    undefined
  );
  const [updateNotificationError, setUpdateNotificationError] = useState(false);
  const [updateNotificationSuccess, setUpdateNotificationSuccess] =
    useState(false);

  useEffect(() => {
    if (!isLoading && data?.data) {
      setIsTelegramNotificationsEnabled(
        (data!.data as NotificationsData).telegram_bot_active
      );
      setApiKey((data!.data as NotificationsData).telegram_bot_api_key);
      setChannelId((data!.data as NotificationsData).telegram_channel_id);
      setNotifyBackups(
        (data!.data as NotificationsData).telegram_notify_backups
      );
      setNotifyMigrations(
        (data!.data as NotificationsData).telegram_notify_migrations
      );
    }
  }, [data, isLoading]);

  const handleSubmit = async (event: FormEvent) => {
    event.preventDefault();

    try {
      const res = await put("/telegram/update-settings", {
        telegram_bot_active: isTelegramNotificationsEnabled,
        telegram_bot_api_key: apiKey,
        telegram_channel_id: channelId,
        telegram_notify_backups: notifyBackups,
        telegram_notify_migrations: notifyMigrations,
      });

      if (res) {
        if (res.status !== 200) {
          clearTimeout(timer1Id);
          setUpdateNotificationError(true);
          setTimer1Id(
            setTimeout(() => setUpdateNotificationError(false), 2000)
          );
          return;
        }
        clearTimeout(timer1Id);
        setUpdateNotificationSuccess(true);
        setTimer1Id(
          setTimeout(() => setUpdateNotificationSuccess(false), 2000)
        );
      }
    } catch (error: any) {
      console.error(error);
      clearTimeout(timer1Id);
      setUpdateNotificationError(true);
      setTimer1Id(setTimeout(() => setUpdateNotificationError(false), 2000));
      return;
    }
  };

  const missingFields =
    isTelegramNotificationsEnabled === "true" ? !apiKey || !channelId : false;

  return (
    <div
      style={{
        display: "flex",
        flexDirection: "column",
        alignItems: "center",
        justifyContent: "space-between",
        width: "84vw",
        marginTop: "100px",
      }}
    >
      <Box
        component="form"
        sx={{
          display: "flex",
          flexDirection: "column",
          width: "300px",
          margin: "0 auto",
        }}
        onSubmit={handleSubmit}
      >
        <FormControlLabel
          control={
            <Checkbox
              checked={isTelegramNotificationsEnabled === "true"}
              onChange={(e) =>
                setIsTelegramNotificationsEnabled(
                  e.target.checked ? "true" : "false"
                )
              }
              name="telegramNotifications"
            />
          }
          label="Telegram Notifications Enabled"
        />
        <TextField
          label="API Key"
          variant="outlined"
          value={apiKey}
          required
          type="password"
          disabled={isTelegramNotificationsEnabled === "false"}
          onChange={(e) => setApiKey(e.target.value)}
          margin="normal"
        />
        <TextField
          label="Channel ID"
          variant="outlined"
          value={channelId}
          required
          disabled={isTelegramNotificationsEnabled === "false"}
          onChange={(e) => setChannelId(e.target.value)}
          margin="normal"
        />
        <FormControlLabel
          control={
            <Checkbox
              checked={notifyBackups === "true"}
              disabled={isTelegramNotificationsEnabled === "false"}
              onChange={(e) =>
                setNotifyBackups(e.target.checked ? "true" : "false")
              }
              name="notifyBackups"
            />
          }
          label="Notify Backups"
        />
        <FormControlLabel
          control={
            <Checkbox
              checked={notifyMigrations === "true"}
              disabled={isTelegramNotificationsEnabled === "false"}
              onChange={(e) =>
                setNotifyMigrations(e.target.checked ? "true" : "false")
              }
              name="notifyMigrations"
            />
          }
          label="Notify Migrations"
        />
        <Button
          variant="contained"
          type="submit"
          sx={{ marginTop: "16px", marginBottom: "16px" }}
          disabled={missingFields}
        >
          Update
        </Button>
        {updateNotificationError && (
          <Alert severity="error">Update failed</Alert>
        )}
        {updateNotificationSuccess && (
          <Alert severity="success">Profile updated</Alert>
        )}
        {!updateNotificationError && !updateNotificationSuccess && (
          <Alert severity="info" sx={{ visibility: "hidden" }}></Alert>
        )}
      </Box>
    </div>
  );
}

export default Notifications;
