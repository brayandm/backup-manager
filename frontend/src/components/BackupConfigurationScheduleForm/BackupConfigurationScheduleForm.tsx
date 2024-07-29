"use client";

import {
  Checkbox,
  FormControlLabel,
  FormControl,
  InputLabel,
  Select,
  MenuItem,
} from "@mui/material";
import React, { useEffect, useState } from "react";

function parseCrontab(cron: string) {
  const parts = cron.split(" ");

  if (parts.length !== 5) {
    throw new Error("Crontab format must have exactly 5 parts");
  }

  const [minute, hour, dayOfMonth, month, dayOfWeek] = parts;

  function parsePart(part: string) {
    if (part.includes("/")) {
      const [base, step] = part.split("/");
      return { base, step };
    }
    return { base: part, step: null };
  }

  return {
    minute: parsePart(minute),
    hour: parsePart(hour),
    dayOfMonth: parsePart(dayOfMonth),
    month: parsePart(month),
    dayOfWeek: parsePart(dayOfWeek),
  };
}

function constructCrontab({ minute, hour, dayOfMonth, month, dayOfWeek }) {
  function constructPart({ base, step }) {
    if (step) {
      return `${base}/${step}`;
    }
    return base;
  }

  const cron = [
    constructPart(minute),
    constructPart(hour),
    constructPart(dayOfMonth),
    constructPart(month),
    constructPart(dayOfWeek),
  ];

  return cron.join(" ");
}

interface BackupConfigurationScheduleFormProps {
  scheduleCron: string;
  setScheduleCron: React.Dispatch<React.SetStateAction<string>>;
  manualBackup: boolean;
  setManualBackup: React.Dispatch<React.SetStateAction<boolean>>;
  setMissingValues: React.Dispatch<React.SetStateAction<boolean>>;
}

function BackupConfigurationScheduleForm({
  scheduleCron,
  setScheduleCron,
  manualBackup,
  setManualBackup,
  setMissingValues,
}: BackupConfigurationScheduleFormProps) {
  const initialCron = parseCrontab(scheduleCron);

  const [minute, setMinute] = useState(initialCron.minute.base);
  const [minuteType, setMinuteType] = useState(
    initialCron.minute.step ? "every" : "at"
  );
  const [hour, setHour] = useState(initialCron.hour.base);
  const [hourType, setHourType] = useState(
    initialCron.hour.step ? "every" : "at"
  );
  const [dayOfMonth, setDayOfMonth] = useState(initialCron.dayOfMonth.base);
  const [dayOfMonthType, setDayOfMonthType] = useState(
    initialCron.dayOfMonth.step ? "every" : "at"
  );
  const [month, setMonth] = useState(initialCron.month.base);
  const [monthType, setMonthType] = useState(
    initialCron.month.step ? "every" : "at"
  );
  const [dayOfWeek, setDayOfWeek] = useState(initialCron.dayOfWeek.base);
  const [dayOfWeekType, setDayOfWeekType] = useState(
    initialCron.dayOfWeek.step ? "every" : "at"
  );

  useEffect(() => {
    if (!manualBackup) {
      if (
        minute === "" ||
        hour === "" ||
        dayOfMonth === "" ||
        month === "" ||
        dayOfWeek === ""
      ) {
        setMissingValues(true);
      } else {
        setMissingValues(false);
      }
    } else {
      setMissingValues(false);
    }
  }, [
    minute,
    hour,
    dayOfMonth,
    month,
    dayOfWeek,
    manualBackup,
    setMissingValues,
  ]);

  useEffect(() => {
    if (minuteType === "every" && minute === "0") {
      setMinute("1");
    }

    if (hourType === "every" && hour === "0") {
      setHour("1");
    }
  }, [minuteType, minute, hourType, hour]);

  return (
    <div
      style={{
        display: "flex",
        flexDirection: "column",
        gap: "16px",
      }}
    >
      <div
        style={{
          display: "flex",
          flexDirection: "row",
          gap: "16px",
        }}
      >
        <FormControl
          sx={{
            width: "100px",
          }}
        >
          <InputLabel id="minute-type">Type</InputLabel>
          <Select
            value={minuteType}
            id="minute-type"
            labelId="minute-type"
            variant="outlined"
            label="Type"
            disabled={manualBackup}
            onChange={(event) => {
              setMinuteType(event.target.value);
            }}
            size="medium"
          >
            <MenuItem key="at" value="at">
              At
            </MenuItem>
            <MenuItem key="every" value="every">
              Every
            </MenuItem>
          </Select>
        </FormControl>
        {minuteType === "at" ? (
          <FormControl
            sx={{
              width: "200px",
            }}
          >
            <InputLabel id="minute">Minute *</InputLabel>
            <Select
              value={minute}
              id="minute"
              labelId="minute"
              variant="outlined"
              label="Minute"
              disabled={manualBackup}
              required={!manualBackup}
              onChange={(event) => {
                setMinute(event.target.value);
              }}
              size="medium"
            >
              {Array.from({ length: 60 }, (_, i) => i).map((minute) => (
                <MenuItem key={"at-" + minute} value={minute}>
                  {minute}
                </MenuItem>
              ))}
            </Select>
          </FormControl>
        ) : (
          <FormControl
            sx={{
              width: "200px",
            }}
          >
            <InputLabel id="minute">Minute *</InputLabel>
            <Select
              value={minute == "0" ? "1" : minute}
              id="minute"
              labelId="minute"
              variant="outlined"
              label="Minute"
              disabled={manualBackup}
              required={!manualBackup}
              onChange={(event) => {
                setMinute(event.target.value);
              }}
              size="medium"
            >
              {Array.from({ length: 59 }, (_, i) => i + 1).map((minute) => (
                <MenuItem key={"every-" + minute} value={minute}>
                  {minute}
                </MenuItem>
              ))}
            </Select>
          </FormControl>
        )}
      </div>
      <div
        style={{
          display: "flex",
          flexDirection: "row",
          gap: "16px",
        }}
      >
        <FormControl
          sx={{
            width: "100px",
          }}
        >
          <InputLabel id="hour-type">Type</InputLabel>
          <Select
            value={hourType}
            id="hour-type"
            labelId="hour-type"
            variant="outlined"
            label="Type"
            disabled={manualBackup}
            onChange={(event) => {
              setHourType(event.target.value);
            }}
            size="medium"
          >
            <MenuItem key="at" value="at">
              At
            </MenuItem>
            <MenuItem key="every" value="every">
              Every
            </MenuItem>
          </Select>
        </FormControl>
        {hourType === "at" ? (
          <FormControl
            sx={{
              width: "200px",
            }}
          >
            <InputLabel id="hour">Hour *</InputLabel>
            <Select
              value={hour}
              id="hour"
              labelId="hour"
              variant="outlined"
              label="Hour"
              disabled={manualBackup}
              required={!manualBackup}
              onChange={(event) => {
                setHour(event.target.value);
              }}
              size="medium"
            >
              {Array.from({ length: 24 }, (_, i) => i).map((hour) => (
                <MenuItem key={"at-" + hour} value={hour}>
                  {hour}
                </MenuItem>
              ))}
            </Select>
          </FormControl>
        ) : (
          <FormControl
            sx={{
              width: "200px",
            }}
          >
            <InputLabel id="hour">Hour *</InputLabel>
            <Select
              value={hour == "0" ? "1" : hour}
              id="hour"
              labelId="hour"
              variant="outlined"
              label="Hour"
              disabled={manualBackup}
              required={!manualBackup}
              onChange={(event) => {
                setHour(event.target.value);
              }}
              size="medium"
            >
              {Array.from({ length: 23 }, (_, i) => i + 1).map((hour) => (
                <MenuItem key={"every-" + hour} value={hour}>
                  {hour}
                </MenuItem>
              ))}
            </Select>
          </FormControl>
        )}
      </div>
      <div
        style={{
          display: "flex",
          flexDirection: "row",
          gap: "16px",
        }}
      >
        <FormControl
          sx={{
            width: "100px",
          }}
        >
          <InputLabel id="day-of-month-type">Type</InputLabel>
          <Select
            value={dayOfMonthType}
            id="day-of-month-type"
            labelId="day-of-month-type"
            variant="outlined"
            label="Type"
            disabled={manualBackup}
            onChange={(event) => {
              setDayOfMonthType(event.target.value);
            }}
            size="medium"
          >
            <MenuItem key="at" value="at">
              At
            </MenuItem>
            <MenuItem key="every" value="every">
              Every
            </MenuItem>
          </Select>
        </FormControl>
        <FormControl
          sx={{
            width: "200px",
          }}
        >
          <InputLabel id="day-of-month">Day of month *</InputLabel>
          <Select
            value={dayOfMonth}
            id="day-of-month"
            labelId="day-of-month"
            variant="outlined"
            label="Day of month"
            disabled={manualBackup}
            required={!manualBackup}
            onChange={(event) => {
              setDayOfMonth(event.target.value);
            }}
            size="medium"
          >
            {Array.from({ length: 31 }, (_, i) => i + 1).map((day) => (
              <MenuItem key={day} value={day}>
                {day}
              </MenuItem>
            ))}
          </Select>
        </FormControl>
      </div>
      <div
        style={{
          display: "flex",
          flexDirection: "row",
          gap: "16px",
        }}
      >
        <FormControl
          sx={{
            width: "100px",
          }}
        >
          <InputLabel id="month-type">Type</InputLabel>
          <Select
            value={monthType}
            id="month-type"
            labelId="month-type"
            variant="outlined"
            label="Type"
            disabled={manualBackup}
            onChange={(event) => {
              setMonthType(event.target.value);
            }}
            size="medium"
          >
            <MenuItem key="at" value="at">
              At
            </MenuItem>
            <MenuItem key="every" value="every">
              Every
            </MenuItem>
          </Select>
        </FormControl>
        {monthType === "at" ? (
          <FormControl
            sx={{
              width: "200px",
            }}
          >
            <InputLabel id="month">Month *</InputLabel>
            <Select
              value={month}
              id="month"
              labelId="month"
              variant="outlined"
              label="Month"
              disabled={manualBackup}
              required={!manualBackup}
              onChange={(event) => {
                setMonth(event.target.value);
              }}
              size="medium"
            >
              {[
                "January",
                "February",
                "March",
                "April",
                "May",
                "June",
                "July",
                "August",
                "September",
                "October",
                "November",
                "December",
              ].map((month, index) => (
                <MenuItem key={index} value={index + 1}>
                  {month}
                </MenuItem>
              ))}
            </Select>
          </FormControl>
        ) : (
          <FormControl
            sx={{
              width: "200px",
            }}
          >
            <InputLabel id="month">Month *</InputLabel>
            <Select
              value={month}
              id="month"
              labelId="month"
              variant="outlined"
              label="Month"
              disabled={manualBackup}
              required={!manualBackup}
              onChange={(event) => {
                setMonth(event.target.value);
              }}
              size="medium"
            >
              {Array.from({ length: 12 }, (_, i) => i + 1).map((month) => (
                <MenuItem key={month} value={month}>
                  {month}
                </MenuItem>
              ))}
            </Select>
          </FormControl>
        )}
      </div>
      <div
        style={{
          display: "flex",
          flexDirection: "row",
          gap: "16px",
        }}
      >
        <FormControl
          sx={{
            width: "100px",
          }}
        >
          <InputLabel id="day-of-week-type">Type</InputLabel>
          <Select
            value={dayOfWeekType}
            id="day-of-week-type"
            labelId="day-of-week-type"
            variant="outlined"
            label="Type"
            disabled={manualBackup}
            onChange={(event) => {
              setDayOfWeekType(event.target.value);
            }}
            size="medium"
          >
            <MenuItem key="at" value="at">
              At
            </MenuItem>
            <MenuItem key="every" value="every">
              Every
            </MenuItem>
          </Select>
        </FormControl>
        {dayOfWeekType === "at" ? (
          <FormControl
            sx={{
              width: "200px",
            }}
          >
            <InputLabel id="day-of-week">Day of week *</InputLabel>
            <Select
              value={dayOfWeek}
              id="day-of-week"
              labelId="day-of-week"
              variant="outlined"
              label="Day of week"
              disabled={manualBackup}
              required={!manualBackup}
              onChange={(event) => {
                setDayOfWeek(event.target.value);
              }}
              size="medium"
            >
              {[
                "Monday",
                "Tuesday",
                "Wednesday",
                "Thursday",
                "Friday",
                "Saturday",
                "Sunday",
              ].map((dayOfWeek, index) => (
                <MenuItem key={index} value={index + 1}>
                  {dayOfWeek}
                </MenuItem>
              ))}
            </Select>
          </FormControl>
        ) : (
          <FormControl
            sx={{
              width: "200px",
            }}
          >
            <InputLabel id="day-of-week">Day of week *</InputLabel>
            <Select
              value={dayOfWeek}
              id="day-of-week"
              labelId="day-of-week"
              variant="outlined"
              label="Day of week"
              disabled={manualBackup}
              required={!manualBackup}
              onChange={(event) => {
                setDayOfWeek(event.target.value);
              }}
              size="medium"
            >
              {Array.from({ length: 7 }, (_, i) => i + 1).map((dayOfWeek) => (
                <MenuItem key={dayOfWeek} value={dayOfWeek}>
                  {dayOfWeek}
                </MenuItem>
              ))}
            </Select>
          </FormControl>
        )}
      </div>

      <FormControlLabel
        control={
          <Checkbox
            checked={manualBackup}
            onChange={(event) => {
              setManualBackup(event.target.checked);
            }}
            color="primary"
          />
        }
        label="Backup manual"
      />
    </div>
  );
}

export default BackupConfigurationScheduleForm;
